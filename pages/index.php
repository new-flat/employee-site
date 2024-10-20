<?php
require_once __DIR__ . '/../controll/xss.php';

// セッションの有効期限を1時間に設定
session_set_cookie_params(3600);

session_start();

// ログイン成功時にセッションIDを再生成（セッション固定攻撃対策）
session_regenerate_id(true);

// CSRFトークンの生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ログアウトが押されたらセッションを終了（ログイン状態解除）
if (isset($_POST['logout'])) {
    session_unset();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員管理システム</title>
    <link rel="stylesheet" href='/employee_site/css/style.css'>
</head>
<body>
    <div class="form-box">
        <form action="/employee_site/controll/login.php" method="POST" class="form">
            <!-- 新規登録成功メッセージ -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <p class="success_message">登録しました</p>
            <?php endif; ?>
            <input type='hidden' name='csrf_token' value='<?php echo eh($_SESSION['csrf_token']); ?>'>
            <?php if (isset($_SESSION['error'])): ?>
                <!-- unset()でセッションから'error'キーを削除し再度表示されないようにする -->
                <p class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>  
            <span class="title">ログイン</span>
            <span class="subtitle">メールアドレスとパスワードを入力してください</span>
            <div class="form-container">
                <input class="input" type="email" name="email" placeholder="メールアドレス">
                <input class="input" type="password" name="pass" placeholder="パスワード">
            </div>
            <button type="submit">ログイン</button>
        </form>
        <div class="form-section">
            <p>未登録の方はこちら<a href="for_unregistered.php">社員登録</a></p>
        </div>
    </div>
</body>
</html>
