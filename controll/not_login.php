<?php

session_start();

// ログイン状態の確認
if (!isset($_SESSION['username'])) {
    // ログインしていない場合、ログイン画面にリダイレクト
    header('Location: /php_lesson/pages/login_form.php');
    exit();
}


?>