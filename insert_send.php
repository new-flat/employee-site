<?php

require_once("error_message.php");

$errors = array();
$insertName = $_POST['insertName'];
$insertKana = $_POST['insertKana'];
$insertGender = $_POST['insertGender'];
$insertDate = $_POST['insertDate'];

// 必須項目のチェック
if (empty($insertName)) {
    $errors["insertName"] = $error_message1;
}
if (empty($insertKana)) {
    $errors["insertKana"] = $error_message2;
}


if (empty($errors)) {
    // DB接続
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root");
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    // データベースに登録
    $sql = "INSERT INTO `php-test` (`username`, `kana`, `gender`, `birth_date`) VALUES (:username, :kana, :gender, :birth_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $insertName, PDO::PARAM_STR);
    $stmt->bindValue(':kana', $insertKana, PDO::PARAM_STR);
    $stmt->bindValue(':gender', $insertGender, PDO::PARAM_INT);
    $stmt->bindValue(':birth_date', $insertDate, PDO::PARAM_STR);

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


?>