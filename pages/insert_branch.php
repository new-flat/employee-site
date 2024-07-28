<?php
require_once 'header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../controll/branch_controll.php';
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/error_message.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支店登録</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>

<body>
    <div id="main" class="wrapper">
        <div id="menu-title" class="wrapper">
            <h1 class="title-name">支店登録</h1>
        </div>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p style="margin:0">登録しました</p>
        <?php endif; ?>

        <form action="insert_branch2.php" method="POST" class="edit-class">
            <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
            <div>
                <div class="label">
                    <label class="insertLabel">支店名</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertBranch" value="<?php echo eh($_POST['insertBranch'] ?? ''); ?>">
                <?php if (!empty($errors['insertBranch'])): ?>
                    <p><?php echo eh($errors['insertBranch']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <div class="label">
                    <label class="insertLabel">住所</label>
                    <p class="insertMust">必須</p>
                </div>
                <div>
                    <select id="prefecture" name="insertPrefecture">
                        <option value="">選択してください</option>
                        <?php foreach ($prefectures as $key => $prefecture): ?>
                            <option value="<?php echo eh($key); ?>" <?php echo (isset($_POST['insertPrefecture']) && $_POST['insertPrefecture'] === $key) ? 'selected' : ''; ?>><?php echo eh($prefecture); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <input type="text" name="insertCity" value="<?php echo eh($_POST['insertCity'] ?? ''); ?>" placeholder="市区町村">
                    <?php if (!empty($errors['insertCity'])): ?>
                        <p><?php echo eh($errors['insertCity']); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="text" name="insertAddress" value="<?php echo eh($_POST['insertAddress'] ?? ''); ?>" placeholder="番地">
                    <?php if (!empty($errors['insertAddress'])): ?>
                        <p><?php echo eh($errors['insertAddress']); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="text" name="insertBuilding" value="<?php echo eh($_POST['insertBuilding'] ?? ''); ?>" placeholder="建物名（任意）">
                    <?php if (!empty($errors['insertBuilding'])): ?>
                        <p><?php echo eh($errors['insertBuilding']); ?></p>
                    <?php endif; ?>
                </div>              
            </div>

            <div>
                <div class="label">
                    <label class="insertLabel">電話番号</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertTel" value="<?php echo eh($errors['data']['insertTel'] ?? $user->tel ?? ''); ?>">
                <?php if (!empty($errors['insertTel'])): ?>
                    <p class="error"><?php echo eh($errors['insertTel']); ?></p>
                <?php endif; ?>
            </div>  

            <div>
                <div class="label">
                    <label class="insertLabel">並び順</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertId" value="<?php echo eh($errors['data']['insertId'] ?? $user->id ?? ''); ?>">
                <?php if (!empty($errors['insertId'])): ?>
                    <p class="error"><?php echo eh($errors['insertId']); ?></p>
                <?php endif; ?>
            </div>                
                    
            <!-- 保存ボタン -->
            <input class="edit-submit" type="submit" value="登録" name="edit">
        </form>
    </div>
</body>

</html>
