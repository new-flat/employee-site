<?php
require_once 'header.php';
require_once __DIR__ . '/../controll/xss.php';
require_once __DIR__ . '/../controll/not_login.php';


// ログイン中社員のユーザー情報を取得
$userId = $_SESSION['id']; //セッションからユーザーIDを取得
$pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root",
[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// 社員情報取得
$sql = "SELECT * FROM employee WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 氏名、生年月日（年齢）の取得
$prof_name = $user['username'];
$prof_birthDate = $user['birth_date'];
// 現在の日付を取得
$currentDate = date('Ymd');
// 生年月日を処理
$birthDate = str_replace("-", "", $prof_birthDate);
// 年齢を計算
$prof_age = floor(($currentDate - $birthDate) / 10000);
if ($prof_age === false) {
    return null;
}
// プロフィール画像と紹介文の取得
$prof_Img = $user['image'];
$introText = $user['intro_text'];



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール編集</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>
<body>
    <div id="main" class="wrapper">
        <div id="menu-title" class="wrapper">
            <h1 class="title-name">プロフィール編集</h1>
        </div>
        <!-- 成功メッセージ -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 4) : ?>
            <p style="margin:0">更新しました</p>
        <?php endif ?>
        <div id="profile-tabel" class="profile">
            <p class="profile-cell">氏名</p>
            <p class="profile-cell"><?php echo eh($prof_name); ?></p>
            <p class="profile-cell">生年月日</p>
            <p class="profile-cell"><?php echo eh($prof_birthDate); ?> 
            (<?php echo eh($prof_age); ?>歳)</p>
        </div>
        <form action="/php_lesson/controll/profile_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="id" value="<?php echo eh($userId); ?>">
            <div class="profile">
                <h3>プロフィール画像</h3>
                <?php if (!empty($prof_Img)): ?>
                    <div>
                        <img src="/php_lesson/images/<?php echo eh($prof_Img); ?>" alt="プロフィール画像">
                    </div>
                    <div>
                        <input type="file" name="profImage">
                    </div>
                    <div>
                        <label><input type="checkbox" name="deleteImage" value="1">削除する</label>
                    </div>
                <?php else: ?> 
                    <div>
                        <input type="file" name="profImage">
                    </div>
                <?php endif; ?>    
            </div>
            <div class="profile">
                <h3>紹介文</h3>
                <textarea name="introText"><?php echo eh($introText); ?></textarea>
            </div>
            <button type="submit" class="edit-submit" style="margin-left: 50px;">登録</button>
        </form>  
    </div>
</body>
</html>