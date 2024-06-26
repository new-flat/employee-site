<?php

require_once("error_message.php");

$errors = array();
$user = null;
if (isset($_GET["id"])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root");
        $edit_sql = "SELECT * FROM `php-test` WHERE id = :id";
        $edit_stmt = $pdo->prepare($edit_sql);
        $edit_stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $edit_stmt->execute();
        $user = $edit_stmt->fetch(PDO::FETCH_OBJ); // 1件のデータを取得
        
        if (!$user) {
            $errors['id'] = $error_message5;
        }
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
} else {
    $errors['id'] = $error_message5;
}

if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員編集</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="header" class="wrapper">
       <h1>社員編集</h1>
    </div>
    <div id="main" class="wrapper">
        <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
            <p style = "margin:0">更新しました</p>
        <?php endif ?>

        <?php if (isset($errors['id'])): ?>
            <p style="margin:0"><?php echo $errors['id']; ?></p>
        <?php else: ?>
            <form action="edit_send2.php" method="POST" class="edit-class">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8'); ?>">
                
                <div>
                    <div class="label">
                        <label class="insertLabel">氏名</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editName" value="<?php echo htmlspecialchars($errors['data']['editName'] ?? $user->username ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if (!empty($errors['messages']['editName'])): ?>
                        <p><?php echo $errors['messages']['editName']; ?></p>
                    <?php endif; ?>    

                </div>
                <div>
                    <div class="label">
                        <label class="insertLabel">かな</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editKana" value="<?php echo htmlspecialchars($errors['data']['editKana'] ?? $user->kana ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if (!empty($errors['messages']['editKana'])) : ?>
                        <p class="error"><?php echo $errors['messages']['editKana']; ?></p>
                    <?php endif; ?>

                </div>
                <div>
                    <div class="label">
                        <label class="insertLabel">性別</label> 
                    </div>
                    <select name="editGender">
                        <option value="1" <?php if (($errors['data']['editGender'] ?? $user->gender) == 1){echo "selected";}; ?>>男</option>
                        <option value="2" <?php if (($errors['data']['editGender'] ?? $user->gender) == 2){echo "selected";}; ?>>女</option>
                        <option value="" <?php if (($errors['data']['editGender'] ?? $user->gender) === null){echo "selected";}; ?>>不明</option>
                    </select>
                </div>
                <div>
                    <div class="label">
                        <label class="insertLabel">生年月日</label>
                    </div>
                    <input type="date" name="editDate" value="<?php echo htmlspecialchars($errors['data']['editDate'] ?? $user->birth_date ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <input class="edit-btn" type="submit" value="登録" name="edit">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
