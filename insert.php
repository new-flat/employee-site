<!-- 社員データ登録画面 -->

<?php  

require_once("header.html");
require_once("controll.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員登録</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="main" class="wrapper">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p style="margin:0">登録しました</p>
        <?php  endif ?>    
        <form action="insert_send.php" method="POST" class="insert-class">
            <div>
                <div class="label">
                    <label class="insertLabel">氏名</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertName" value="<?php echo htmlspecialchars($_POST['insertName'] ?? '', ENT_QUOTES); ?>">
                <?php if (!empty($errors["insertName"])): ?>
                    <p><?php echo $errors["insertName"]; ?></p>
                <?php endif; ?>    
            </div>
            <div>
                <div class="label">
                    <label class="insertLabel">かな</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertKana" value="<?php echo htmlspecialchars($_POST['insertKana'] ?? '', ENT_QUOTES); ?>">
                <?php if (!empty($errors['insertKana'])) : ?>
                    <p class="error"><?php echo $errors['insertKana']; ?></p>
                <?php endif; ?>
            </div>
            <div>
                <div class="label">
                    <label class="insertLabel">性別</label> 
                </div>
                <select name="insertGender" >
                    <option value="" selected>選択</option>
                    <option value="1" <?php echo (isset($_POST['insertGender']) && $_POST['insertGender'] === '1') ? 'selected' : ''; ?>>男</option>
                    <option value="2" <?php echo (isset($_POST['insertGender']) && $_POST['insertGender'] === '2') ? 'selected' : ''; ?>>女</option>
                    <option value="null" <?php echo (isset($_POST['insertGender']) && $_POST['insertGender'] === 'null') ? 'selected' : ''; ?>>不明</option>
                </select>
            </div>
            <div>
            <div class="label">
                    <label class="insertLabel">生年月日</label>
                </div>
                <input type="date" name="insertDate" value="<?php echo htmlspecialchars($_POST['insertDate'] ?? '', ENT_QUOTES); ?>">
            </div>
            
            <input class="insert-btn" type="submit" value="登録" name="insert">
           
        </form>
    </div>
</body>
</html>