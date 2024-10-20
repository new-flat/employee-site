<?php
require_once 'pdo_connect.php';

$_SESSION['error'] = array();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("リクエストが無効です");
}

// フォームの入力値を取得
$name = $_POST['name'];
$kana = $_POST['kana'];
$email = $_POST['email'];
$password = $_POST['pass'];

// 項目が入力されているかチェック
if (empty($_POST['kana'] || $_POST['name'] || $_POST['email']) || empty($_POST['pass'])) {
    $_SESSION['error'] = '項目を全て入力してください。';
    header('Location: /employee_site/pages/for_unregistered.php');
    exit();
}

// パスワードの長さチェック
if (strlen($password) < 8) {
    $_SESSION['error'] = 'パスワードは8文字以上の半角英数字で入力してください';
} else {
    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
}

// エラーがなければDBに登録
if (empty($_SESSION['error'])) {
    try {
        $pdo = getPdoConnection();
        $sql = "INSERT employee (username, kana, email, password) VALUES(?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $kana, $email, $hashedPass]);

        // 成功時にログインページにリダイレクト
        header('Location: /employee_site/pages/index.php?success=1');
        exit();    
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

?>

