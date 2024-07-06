<?php

require_once("error_message.php");
require_once("header.php");

$errors = array();

// CSRF対策用トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        die("CSRFトークンがありません");
    }


    // トークンが一致するか確認　
    // ⏬なぜhash_equalsを使うのか？
    // 通常の比較演算子(`==`や`===`)を使うと、文字列の長さや部分的な一致によって処理時間が変わることがあり、
    // これを攻撃者が悪用して文字列を推測するリスクがあります。hash_equalsは文字列の全体を一度に比較し、
    // 常に一定の時間をかけるため、タイミング攻撃を防止するのに適しています。
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // トークンが一致しない場合
        die ("無効なCSRFトークン");
    }   

    $insertName = $_POST['insertName'];
    $insertKana = $_POST['insertKana'];
    $insertGender = $_POST['insertGender'];
    $insertDate = $_POST['insertDate'];
    $insertEmail = $_POST['insertEmail'];
    $insertCommute = isset($_POST['insertCommute']) ? trim($_POST['insertCommute']) : null ;
    $insertBlood = $_POST['insertBlood'] ?? null;
    $insertMarried = $_POST['insertMarried'] ?? null;   

    // 必須項目のチェック
    if (empty($insertName)) {
        $errors["insertName"] = $error_message1;
    }
    if (empty($insertKana)) {
        $errors["insertKana"] = $error_message2;
    }
    if (empty($insertEmail)) {
        $errors['insertEmail'] = $error_message6;
    }
    if (empty($insertBlood)) {
        $errors['insertBlood'] = $error_message7;
    }

    // 通勤時間のバリデーション（1以上の整数かどうか）
    if (!empty($insertCommute)) {
        if (!filter_var($insertCommute, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            $errors['insertCommute'] = "1以上の整数を入力してください";
        }
    }

    // 入力内容をデータベースに登録
    if (empty($errors)) {
        // DB接続
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root");
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        $sql = "INSERT INTO `php-test` (`username`, `kana`, `gender`, `birth_date`, `email`, `commute_time`, `blood_type`, `married`) VALUES (:username, :kana, :gender, :birth_date, :email, :commute_time, :blood_type, :married)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $insertName, PDO::PARAM_STR);
        $stmt->bindValue(':kana', $insertKana, PDO::PARAM_STR);
        $stmt->bindValue(':gender', $insertGender, PDO::PARAM_INT);
        $stmt->bindValue(':birth_date', $insertDate, PDO::PARAM_STR);
        $stmt->bindValue(':email', $insertEmail, PDO::PARAM_STR);
        $stmt->bindValue(':commute_time', $insertCommute, PDO::PARAM_INT);
        $stmt->bindValue(':blood_type', $insertBlood, PDO::PARAM_INT);
        $stmt->bindValue(':married', $insertMarried, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // 成功したら同じページ（insert.php）に戻り、URLを追加してメッセージを表示
            header('Location: insert.php?success=1');
            // リダイレクト後に不要なコードが実行されないようにする
            exit;
        } else {
            echo '登録に失敗しました';
        }

        $pdo = null;

    } else {
        // エラーがある場合はフォームに戻る
        include "insert.php";
    }
}


?>