<?php
require_once __DIR__ . '/../controll/xss.php';

session_start();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員管理システム</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>
<body>
    <div id="main" class="wrapper" style="padding-bottom: 70px;">
        <div id="menu-title" class="wrapper">
            <h1 class="login-title">ログイン</h1>
        </div>
        <div class="login-box">
        <p>Login</p>
        <?php if (isset($_SESSION['error'])): ?>
            <!-- unset()でセッションから'error'キーを削除し再度表示されないようにする -->
            <p style="color:white;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>    
        <form action="/php_lesson/controll/login.php" method="POST">
            <div class="user-box">
            <input name="email" type="text" >
            <label>メールアドレス</label>
            </div>
            <div class="user-box">
            <input name="pass" type="password">
            <label>パスワード</label>
            </div>
            <button type="submit">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            ログイン
            </button>
        </form>
        <p>アカウントをお持ちでないですか? <a href="insert_employee.php" class="a2">登録する</a></p>
        </div> 
    </div>   
</body>
</html>