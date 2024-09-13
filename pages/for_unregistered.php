<?php

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>
<body>
    <div class="form-box">
        <form action="/php_lesson/controll/unregistered.php" method="POST" class="form" >
            <span class="title">社員登録</span>
            <span class="subtitle">メールアドレスと
            パスワードを入力してください</span>
            <div class="form-container">
                <input type="text" name="name" class="input" placeholder="フルネーム">
                <input type="text" name="kana" class="input" placeholder="ふりがな">
                <input type="email" name="email" class="input" placeholder="メールアドレス">
                <input type="password" name="pass" class="input" placeholder="パスワード">
            </div>
            <button type="submit">登録</button>
        </form>
        <div class="form-section">
        <p>既にアカウントをお持ちですか? <a href="">ログイン</a> </p>
        </div>
    </div>
</body>
</html>