<?php
require_once 'header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/not_login.php';

$errors = $_SESSION['errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];

// セッションに保持していたエラーメッセージとフォームデータをクリア
unset($_SESSION['errors'], $_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支店登録</title>
    <link rel="stylesheet" href='/employee_site/css/style.css'>
</head>

<body>
    <div id="main" class="wrapper">
        <div id="menu-title">
            <h1 class="title-name">支店登録</h1>
        </div>

        <form action="/employee_site/controll/insertB_controll.php" method="POST" class="edit-class">

            <!-- 登録成功メッセージ -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <p class="success-message">登録しました</p>
            <?php endif; ?>

            <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">

            <!-- 支店名 -->
            <div>
                <div class="label">
                    <label class="insertLabel">支店名</label>
                    <p class="insert-must">必須</p>
                </div>
                <input type="text" name="insertBranch" value="<?php echo eh($_POST['insertBranch'] ?? ''); ?>">
                <?php if (!empty($errors['insertBranch'])): ?>
                    <p class="error-message"><?php echo eh($errors['insertBranch']); ?></p>
                <?php endif; ?>
            </div>

            <!-- 住所 -->
            <div>
                <div class="label">
                    <label class="insertLabel">住所</label>
                    <p class="insert-must">必須</p>
                </div>
                <div class="input-access">
                    <select id="prefecture" name="insertPrefecture">
                        <option value="">選択してください</option>
                        <?php foreach ($prefSelect as $key => $pref): ?>
                            <option value="<?php echo eh($key); ?>" <?php echo (isset($_POST['insertPrefecture']) && $_POST['insertPrefecture'] === $key) ? 'selected' : ''; ?>><?php echo eh($pref); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-access">
                    <input type="text" name="insertCity" value="<?php echo eh($_POST['insertCity'] ?? ''); ?>" placeholder="市区町村">

                </div>
                <div class="input-access">
                    <input type="text" name="insertAddress" value="<?php echo eh($_POST['insertAddress'] ?? ''); ?>" placeholder="番地">
                </div>
                <div>
                    <input type="text" name="insertBuilding" value="<?php echo eh($_POST['insertBuilding'] ?? ''); ?>" placeholder="建物名（任意）">
                </div>   
                <?php if (!empty($errors['insertPrefecture'])): ?>
                    <p class="error-message"><?php echo eh($errors['insertPrefecture']); ?></p>
                <?php endif; ?>           
            </div>

            <!-- 電話番号 -->
            <div>
                <div class="label">
                    <label class="insertLabel">電話番号</label>
                    <p class="insert-must">必須</p>
                </div>
                <input type="text" name="insertTel" value="<?php echo eh($errors['data']['insertTel'] ?? $user->tel ?? ''); ?>">
                <?php if (!empty($errors['insertTel'])): ?>
                    <p class="error-message"><?php echo eh($errors['insertTel']); ?></p>
                <?php endif; ?>
            </div>  

            <!-- 並び順（ID） -->
            <div>
                <div class="label">
                    <label class="insertLabel">並び順</label>
                    <p class="insert-must">必須</p>
                </div>
                <input type="text" name="insertId" value="<?php echo eh($errors['data']['insertId'] ?? $user->id ?? ''); ?>">
                <?php if (!empty($errors['insertId'])): ?>
                    <p class="error-message"><?php echo eh($errors['insertId']); ?></p>
                <?php endif; ?>
            </div>                
                    
            <!-- 保存ボタン -->
            <input type="submit" class="submit-btn"  value="登録" name="edit">
        </form>
    </div>
</body>

</html>
