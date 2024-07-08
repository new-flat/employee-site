<?php
// edit_send2.php

require_once("controll.php");
require_once("error_message.php");
require_once("header.php"); // セッション開始とCSRFトークン生成

$errors = array();

// トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        die('リクエストが無効です');
    }

    // トークンが一致するか確認　
    // ⏬なぜhash_equalsを使うのか？
    // 通常の比較演算子(`==`や`===`)を使うと、文字列の長さや部分的な一致によって処理時間が変わることがあり、
    // これを攻撃者が悪用して文字列を推測するリスクがあります。hash_equalsは文字列の全体を一度に比較し、
    // 常に一定の時間をかけるため、タイミング攻撃を防止するのに適しています。
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // トークンが一致する場合
        $id = $_POST['id'] ?? null;
        $editName = $_POST['editName'];
        $editKana = $_POST['editKana'];
        $editGender = $_POST['editGender'];
        $editDate = $_POST['editDate'] ?? null;
        $editEmail = $_POST['editEmail'];
        $editCommute = isset($_POST['editCommute']) ? trim($_POST['editCommute']) : null;
        $editBlood = $_POST['editBlood'];
        $editMarried = $_POST['editMarried'] ?? null;

    } else {
        die ("リクエストが無効です");
    }

    $errors = array();

    if ($editDate === '') {
        $editDate = null;
    }

    // 必須項目のチェック
    if (empty($editName)) {
        $errors['editName'] = $error_message1;
    }
    if (empty($editKana)) {
        $errors['editKana'] = $error_message2;
    }
    if (empty($editEmail)) {
        $errors['editEmail'] = $error_message6;
    }
    if (empty($editBlood)) {
        $errors['editBlood'] = $error_message7;
    }

    // 通勤時間のバリデーション（1以上の整数かどうか）
    if (!empty($editCommute)) {
        if (!filter_var($editCommute, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            $errors['editCommute'] = "1以上の整数を入力してください";
        }
    }

    // 編集データをDBに登録
    if (empty($errors)) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            //トランザクション内のすべての操作が成功した場合のみデータベースに反映。一つでも失敗した場合はすべての操作を取り消し
            $pdo->beginTransaction();

            $update_sql = "UPDATE `php-test` SET username = :username, kana = :kana, gender = :gender, birth_date = :birth_date, email = :email, commute_time = :commute_time, blood_type = :blood_type, married = :married WHERE id = :id";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->bindValue(':username', $editName, PDO::PARAM_STR);
            $update_stmt->bindValue(':kana', $editKana, PDO::PARAM_STR);
            $update_stmt->bindValue(':gender', $editGender === '' ? null : $editGender, PDO::PARAM_INT);
            $update_stmt->bindValue(':birth_date', $editDate, PDO::PARAM_STR);
            $update_stmt->bindValue(':email', $editEmail, PDO::PARAM_STR);
            $update_stmt->bindValue(':commute_time', $editCommute === '' ? null : $editCommute, PDO::PARAM_INT);
            $update_stmt->bindValue(':blood_type', $editBlood, PDO::PARAM_STR);
            $update_stmt->bindValue(':married', $editMarried === '' ? null : $editMarried, PDO::PARAM_INT);
            $update_stmt->bindValue(':id', $id, PDO::PARAM_INT);

            $update_stmt->execute();
            // 'commit'メソッドを使用して、トランザクション内のすべての操作を確定
            $pdo->commit();            
            
            // 成功したら同じページ（edit.php）に戻り、URLを追加してメッセージを表示
            header("Location: edit.php?id=" . $id . "&success=2");
            // リダイレクト後に不要なコードが実行されないようにする
            exit;

        } catch (PDOException $e) {
            // トランザクション内でエラーが発生した場合、操作を取り消し
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // エラーメッセージをログに記録
            error_log($e->getMessage());
        }

        $pdo = null;

    } else {
        // エラーがある場合はフォームに戻る
        $error_data = [
            'editName' => $editName,
            'editKana' => $editKana,
            'editGender' => $editGender,
            'editDate' => $editDate,
            'editEmail' => $editEmail,
            'editCommute' => $editCommute,
            'editBlood' => $editBlood,
            'editMarried' => $editMarried
        ];

        $error_query = http_build_query(['errors' => json_encode(['messages' => $errors, 'data' => $error_data])]);
        header("Location: edit.php?id=" . $id . "&" . $error_query);
        exit;
    }

}


?>
