<?php
require_once 'error_message.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成

$errors = array();

// CSRF対策用トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        die('リクエストが無効です');
    }

    // トークンが一致するか確認
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // フォームの入力値を取得
        $insertName = $_POST['insertName'];
        $insertKana = $_POST['insertKana'];
        $insertBranch = $_POST['insertBranch'];
        $insertGender = $_POST['insertGender'];
        $insertDate = $_POST['insertDate'] ?? null;
        $insertEmail = $_POST['insertEmail'];
        $insertCommute = isset($_POST['insertCommute']) ? trim($_POST['insertCommute']) : null;
        $insertBlood = $_POST['insertBlood'];
        $insertMarried = $_POST['insertMarried'] ?? null;
        $insertPass = $_POST['insertPass'];
    } else {
        die("リクエストが無効です");
    }


    // 入力項目のチェック
    if (empty($_POST['insertName'])) {
        $errors['insertName'] = $error_message1;
    }
    if (empty($_POST['insertKana'])) {
        $errors['insertKana'] = $error_message2;
    }
    if (empty($_POST['insertEmail'])) {
        $errors['insertEmail'] = $error_message6;
    }
    if (empty($_POST['insertBlood'])) {
        $errors['insertBlood'] = $error_message7;
    }
    if (empty($_POST['insertPass'])) {
        $errors['insertPass'] = $error_message3;
    }
    // パスワードの長さチェック(8文字以上)
    if (strlen($insertPass) < 8) {
        $errors['insertPass'] = $error_message13;
    } else {
        // パスワードをハッシュ化
        $hashedPass = password_hash($insertPass, PASSWORD_DEFAULT);
    }

    // 通勤時間のバリデーション（1以上の整数かどうか）
    if (!empty($insertCommute)) {
        if (!filter_var($insertCommute, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            $errors['insertCommute'] = '1以上の整数を入力してください';
        }
    }

    // メールアドレスの重複チェック
    if (empty($errors)) {
        try {
            $pdo = new PDO(
                'mysql:host=localhost;dbname=php-test',
                'root',
                'root',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $sql = "SELECT * FROM employee WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $insertEmail, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch();

            if ($count === $insertEmail) {
                // 他の社員に登録されているメールアドレスと同じメールアドレスが入力された場合
                $errors['insertEmail'] = 'このメールアドレスは既に登録されています';
            } else {
                // トランザクション内のすべての操作が成功した場合のみデータベースに反映
                $pdo->beginTransaction();

                $sql = "INSERT INTO `employee` 
                        (`username`, `kana`, `branch`, `gender`, `birth_date`, `email`,`password`, `commute_time`, `blood_type`, `married`) 
                        VALUES 
                        (:username, :kana, :branch, :gender, :birth_date, :email, :password, :commute_time, :blood_type, :married)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':username', $insertName, PDO::PARAM_STR);
                $stmt->bindValue(':kana', $insertKana, PDO::PARAM_STR);
                $stmt->bindValue(':branch', $insertBranch, PDO::PARAM_INT);
                $stmt->bindValue(':gender', $insertGender, PDO::PARAM_INT);
                $stmt->bindValue(':birth_date', $insertDate, PDO::PARAM_STR);
                $stmt->bindValue(':email', $insertEmail, PDO::PARAM_STR);
                $stmt->bindValue(':password', $hashedPass, PDO::PARAM_STR);
                $stmt->bindValue(':commute_time', $insertCommute, PDO::PARAM_INT);
                $stmt->bindValue(':blood_type', $insertBlood, PDO::PARAM_STR);
                $stmt->bindValue(':married', $insertMarried, PDO::PARAM_INT);

                $stmt->execute();

                // 保有資格の登録
                if (!empty($insertQuali)) {
                    $employeeId = $pdo->lastInsertId();
                    $sql = "INSERT INTO `emp_quali` (`employee_id`, `quali_id`) VALUES (:employee_id, :quali_id)";
                    $stmt = $pdo->prepare($sql);
                    foreach ($insertQuali as $qualiId) {
                        $stmt->bindValue(':employee_id', $employeeId, PDO::PARAM_INT);
                        $stmt->bindValue(':quali_id', $qualiId, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }

                // トランザクションをコミット
                $pdo->commit();

                // 登録成功後のリダイレクト
                header('Location: /php_lesson/pages/insert_employee.php?success=1');
                exit();
            }
        } catch (PDOException $e) {
            // エラー発生時の処理
            $pdo->rollBack();
            echo $e->getMessage();
        }
    } else {
        // エラーがある場合、フォームにエラーメッセージを表示
        $_SESSION['errors'] = $errors;
        header('Location: /php_lesson/pages/insert_employee.php');
        exit();
    }
}
?>
