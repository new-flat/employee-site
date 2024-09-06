<?php
require_once __DIR__ . '/../controll/xss.php';
require_once __DIR__ . '/../controll/branch_function.php';


session_start();

// CSRFトークンの生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(16));
}
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
    <div id="header" class="wrapper">
        <ul class="menu">
            <li class="menu_item"><a href="employee_list.php" class="menu_btn">社員一覧</a></li>
            <li class="menu_item"><a href="insert_employee.php" class="menu_btn">社員登録</a></li>
            <li class="menu_item"><a href="total_view.php" class="menu_btn">社員集計</a></li>
            <li class="menu_item"><a href="branch_list.php" class="menu_btn">支店一覧</a></li>
            <li class="menu_item"><a href="insert_branch.php" class="menu_btn">支店登録</a></li>
            <li class="menu_item"><a href="quali_masta.php" class="menu_btn">資格マスタ</a></li>
            <li class="menu_item"><a href="profile_view.php" class="menu_btn">プロフィール</a></li>
        </ul>
        <?php if (isset($_SESSION['username'])): ?>
        <div class="user-info">
            <p><?php echo eh($_SESSION['username']); ?>さんログイン中</p>
        </div>
        <?php endif; ?>
        
        <form action="/php_lesson/pages/login_form.php" method="post">
            <!-- ログアウトボタン -->
            <button class="logout" onclick="location.href='/php_lesson/pages/login_form.php'" type="submit" name="logout">
            ログアウト
            </button>
        </form>

    </div>
</body>

</html>