<?php
require_once 'pdo_connect.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成

// CSRFトークンの生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("リクエストが無効です");
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
    die('リクエストが無効です');
}

$email = $_POST['email'];
$errors = [];

// 項目が入力されているかチェック
if (empty($_POST['email']) || empty($_POST['pass'])) {
    $_SESSION['error'] = 'メールアドレスとパスワードを入力してください。';
    header('Location: /employee_site/pages/index.php');
    exit();
}

try {
    $pdo = getPdoConnection();

    //employeeテーブルから、postされたメールアドレスに一致するレコードを取得する
    $sql = "SELECT * FROM employee WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    // fetchでクエリの結果から最初のレコードを取得し$memberに格納、存在しなければfalseを返す
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 指定したハッシュがパスワードにマッチしているかチェック
    if ($member && password_verify($_POST['pass'], $member['password'])) {
        // DBのユーザー情報をセッションに保存
        $_SESSION['id'] = $member['id'];
        $_SESSION['username'] = $member['username'];
        $_SESSION['birth_date'] = $member['birth_date'];
    
        //ログイン成功後のリダイレクト先を確認
        $redirect_url = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '/employee_site/pages/employee_list.php';
        unset($_SESSION['redirect_after_login']); //一度リダイレクトしたらセッションクリア
    
        //ログインに成功したらリダイレクト
        header("Location: $redirect_url");
        exit();
    } else { 
        $_SESSION['error'] = "メールアドレスもしくはパスワードが間違っています";
        header('Location: /employee_site/pages/index.php');
        exit();
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

?>