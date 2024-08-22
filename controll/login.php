<?php
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成

session_start();
$errors = [];

// 項目が入力されているかチェック
if (empty($_POST['email']) || empty($_POST['pass'])) {
    $_SESSION['error'] = 'メールアドレスとパスワードを入力してください。';
    header('Location: /php_lesson/pages/login_form.php');
    exit();
}

$email = $_POST['email'];

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=php-test','root','root',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    echo $e->getMessage;
    exit();
}

//employeeテーブルから、postされたメールアドレスに一致するレコードを取得する
$sql = "SELECT * FROM employee WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
// fetchでクエリの結果から最初のレコードを取得し$memberに格納、存在しなければfalseを返す
$member = $stmt->fetch();

// 指定したハッシュがパスワードにマッチしているかチェック
if ($member && password_verify($_POST['pass'], $member['password'])) {
    // DBのユーザー情報をセッションに保存
    $_SESSION['id'] = $member['id'];
    $_SESSION['username'] = $member['username'];
    //ログインに成功したらホーム画面に遷移
    header('Location: /php_lesson/pages/employee_list.php');
    exit();
} else { 
    $_SESSION['error'] = "メールアドレスもしくはパスワードが間違っています";
    header('Location: /php_lesson/pages/login_form.php');
    exit();
}
?>