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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href='/employee_site/css/style.css'>
</head>

<body>
    <div id="header">
        <div class="contents">
            <ul class="mega-menu">
                <li class="menu-parent">
                    <a href="#"class="menu-parent_a">メニュー</a>
                    <div class="menu-child">
                        <ul class="menu">
                            <li class="menu_item"><a href="employee_list.php" class="menu_btn">社員一覧</a></li>
                            <li class="menu_item"><a href="insert_employee.php" class="menu_btn">社員登録</a></li>
                            <li class="menu_item"><a href="total_view.php" class="menu_btn">社員集計</a></li>
                            <li class="menu_item"><a href="branch_list.php" class="menu_btn">支店一覧</a></li>
                            <li class="menu_item"><a href="insert_branch.php" class="menu_btn">支店登録</a></li>
                            <li class="menu_item"><a href="quali_masta.php" class="menu_btn">資格マスタ</a></li>
                            <li class="menu_item"><a href="profile_view.php" class="menu_btn">プロフィール</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
            <div class="login-state">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="user-info">
                        <p><?php echo eh($_SESSION['username']); ?>さんログイン中</p>
                    </div>
                <?php endif; ?>
                
                <form action="/employee_site/pages/index.php" method="post">
                    <!-- ログアウトボタン -->
                    <button class="logout">
                        <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
                        <div class="logout-text">ログアウト</div>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="http://localhost:8888/employee_site/js/menu.js"></script>
</body>

</html>