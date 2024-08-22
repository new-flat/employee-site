<?php

require_once 'header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../controll/branch_controll.php';
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/error_message.php';
require_once __DIR__ . '/../controll/branch_function.php';

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支店編集</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>

<body>
    <div id="main" class="wrapper">
        <div id="menu-title" class="wrapper">
            <h1 class="title-name">支店編集</h1>
        </div>
        <?php if (isset($_GET['success']) && $_GET['success'] == 2) : ?>
            <p style="margin:0">更新しました</p>
        <?php endif ?>

        <?php if (isset($errors['id'])) : ?>
            <p style="margin:0"><?php echo eh($errors['id']); ?></p>
        <?php else : ?>
            <form action="/php_lesson/controll/editB_controll.php" method="POST" class="edit-class">
                <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="originalId" value="<?php echo $data[id]; ?>">
                
                <div>
                    <div class="label">
                        <label class="insertLabel">支店名</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editBranch" value="<?php echo eh($errors['data']['editBranch'] ?? $user->branch_name ?? ''); ?>">
                    <?php if (!empty($errors['messages']['editBranch'])) : ?>
                        <p><?php echo eh($errors['messages']['editBranch']); ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <div class="label">
                        <label class="insertLabel">住所</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <div>
                        <select name="editPrefecture">
                            <option value="">都道府県を選択</option>
                            <?php foreach ($prefectures as $key => $prefecture) : ?>
                                <option value="<?php echo eh($key); ?>" <?php echo $user->prefecture == $key ? 'selected' : ''; ?>>
                                    <?php echo eh($prefecture); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['messages']['editPrefecture'])) : ?>
                            <p class="error"><?php echo eh($errors['messages']['editPrefecture']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <input type="text" name="editCity" value="<?php echo eh($errors['data']['editCity'] ?? $user->city ?? ''); ?>">
                        <?php if (!empty($errors['messages']['editCity'])) : ?>
                            <p class="error"><?php echo eh($errors['messages']['editCity']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <input type="text" name="editAddress" value="<?php echo eh($errors['data']['editAddress'] ?? $user->address ?? ''); ?>">
                        <?php if (!empty($errors['messages']['editAddress'])) : ?>
                            <p class="error"><?php echo eh($errors['messages']['editAddress']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <input type="text" name="editBuild" value="<?php echo eh($errors['data']['editBuild'] ?? $user->building ?? ''); ?>">
                        <?php if (!empty($errors['messages']['editBuilding'])) : ?>
                            <p class="error"><?php echo eh($errors['messages']['editBuilding']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <div class="label">
                        <label class="insertLabel">電話番号</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editTel" value="<?php echo eh($errors['data']['editTel'] ?? $user->tel ?? ''); ?>">
                    <?php if (!empty($errors['messages']['editTel'])) : ?>
                        <p class="error"><?php echo eh($errors['messages']['editTel']); ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <div class="label">
                        <label class="insertLabel">並び順</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editId" value="<?php echo eh($errors['data']['editId'] ?? $user->id ?? ''); ?>">
                    <?php if (!empty($errors['messages']['editId'])) : ?>
                        <p class="error"><?php echo eh($errors['messages']['editId']); ?></p>
                    <?php endif; ?>
                </div>

                <!-- 保存ボタン -->
                <input class="edit-submit" type="submit"  name="edit">
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
