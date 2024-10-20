<?php

// ログイン状態の確認
if (!isset($_SESSION['username'])) {
    // 現在のURLをセッションに保存
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

    // ログインしていない場合、ログイン画面にリダイレクト
    header('Location: /employee_site/pages/index.php');
    exit();
}

?>