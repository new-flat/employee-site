<?php
require_once 'header.php';
require_once __DIR__ . '/../controll/xss.php';
require_once __DIR__ . '/../controll/not_login.php';
require_once __DIR__ . '/../controll/pdo_connect.php';

// ログイン中社員のユーザー情報を取得
$userId = $_SESSION['id']; //セッションからユーザーIDを取得
$pdo = getPdoConnection();

// 社員情報取得
$sql = "SELECT * FROM employee WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 氏名、生年月日（年齢）の取得
$profName = $user['username'];
$profBirthDate = $user['birth_date'];
// 現在の日付を取得
$currentDate = date('Ymd');
// 生年月日を処理(null(未設定)の場合は0を設定)
$birthDate = $profBirthDate !== null ? str_replace("-", "", $profBirthDate) : 0;
// 年齢を計算(生年月日が0の場合は空白で表示)
$profAge = $birthDate !== 0 ? floor(($currentDate - $birthDate) / 10000) : "";
if ($profAge === false) {
    return null;
}
// プロフィール画像と紹介文の取得
$profImg = $user['image'];
$introText = $user['intro_text'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール編集</title>
    <link rel="stylesheet" href='/employee_site/css/style.css'>
</head>
<body>
    <div id="main" class="wrapper">
        <div id="menu-title">
            <h1 class="title-name">プロフィール編集</h1>
        </div>
        <div id="profile-tabel" class="profile">
            <!-- 成功メッセージ -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
                <p class="success-message">更新しました</p>
            <?php endif ?>
            <p class="profile-cell">氏名</p>
            <p class="profile-cell"><?php echo eh($profName); ?></p>
            <p class="profile-cell">生年月日</p>
            <p class="profile-cell"><?php echo eh($profBirthDate); ?> 
            (<?php echo eh($profAge); ?>歳)</p>
        </div>
        <form action="/employee_site/controll/profile_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="id" value="<?php echo eh($userId); ?>">
            <div class="profile">
                <h3>プロフィール画像</h3>
                <?php if (!empty($profImg)): ?>
                    <div>
                        <img src="/employee_site/images/<?php echo eh($profImg); ?>" alt="プロフィール画像">
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
            <button type="submit" class="submit-btn" style="margin-left: 50px;">登録</button>
        </form>  
    </div>
</body>
</html>