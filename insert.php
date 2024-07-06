<!-- 社員データ登録画面 -->

<?php

require_once("header.php");
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
        <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
            <p style="margin:0">登録しました</p>
        <?php endif ?>
        <form action="insert_send.php" method="POST" class="insert-class">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div>
                <div class="label">
                    <label class="insertLabel">氏名</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertName" value="<?php echo htmlspecialchars($_POST['insertName'] ?? '', ENT_QUOTES); ?>">
                <?php if (!empty($errors["insertName"])) : ?>
                    <p><?php echo htmlspecialchars($errors["insertName"], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <div class="label">
                    <label class="insertLabel">かな</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertKana" value="<?php echo htmlspecialchars($_POST['insertKana'] ?? '', ENT_QUOTES); ?>">
                <?php if (!empty($errors['insertKana'])) : ?>
                    <p class="error"><?php echo htmlspecialchars($errors['insertKana'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <div class="label">
                    <label class="insertOption">性別</label>
                </div>
                <select name="insertGender">
                    <option value="" selected>選択</option>
                    <option value="1" <?php echo (isset($_POST['insertGender']) && $_POST['insertGender'] === '1') ? 'selected' : ''; ?>>男</option>
                    <option value="2" <?php echo (isset($_POST['insertGender']) && $_POST['insertGender'] === '2') ? 'selected' : ''; ?>>女</option>
                    <option value="null" <?php echo (isset($_POST['insertGender']) && $_POST['insertGender'] === 'null') ? 'selected' : ''; ?>>不明</option>
                </select>
            </div>
            <div>
                <div class="label">
                    <label class="insertOption">生年月日</label>
                </div>
                <input type="date" name="insertDate" value="<?php echo htmlspecialchars($_POST['insertDate'] ?? '', ENT_QUOTES); ?>">
            </div>
            <div>
                <div class="label">
                    <label class="insertOption">メールアドレス</label>
                    <p class="insertMust">必須</p>
                </div>
                    <input type="text" name="insertEmail" value="<?php echo htmlspecialchars($errors['data']['insertEmail']  ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if (!empty($errors['insertEmail'])) : ?>
                        <p class="error"><?php echo htmlspecialchars($errors['insertEmail'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                    <?php if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insertEmail'])) {
                        $insertEmail = $_POST['insertEmail'];
                            if (!filter_var($insertEmail, FILTER_VALIDATE_EMAIL)) {
                                echo htmlspecialchars("メールアドレスが正しくありません", ENT_QUOTES, 'UTF-8');
                            }
                        }         
                    ?>  
                </div> 
                <div>
                   <div class="label">
                        <label class="insertOption">通勤時間(分)</label>
                   </div>  
                   <input type="type" name="insertCommute" value="<?php echo htmlspecialchars($errors['data']['insertCommute'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                   <?php if (!empty($errors['insertCommute'])) : ?>
                    <p class="error"><?php echo htmlspecialchars($errors['insertCommute'], ENT_QUOTES, 'UTF-8'); ?></p>
                   <?php endif; ?>  
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">血液型</label>
                    </div>
                    <div>
                        <label><input type="radio" name="insertBlood" value="A" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === 'A') ? 'checked' : ''; ?>> A型</label>
                        <label><input type="radio" name="insertBlood"  value="B" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === 'B') ? 'checked' : ''; ?>> B型</label>
                        <label><input type="radio" name="insertBlood"  value="O" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === 'O') ? 'checked' : ''; ?>> O型</label>
                        <label><input type="radio" name="insertBlood"  value="AB" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === 'AB') ? 'checked' : ''; ?>> AB型</label>
                        <label><input type="radio" name="insertBlood"  value="" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === '') ? 'checked' : ''; ?>> 不明</label>
                    </div>
                    <?php if (!empty($errors['insertBlood'])) : ?>
                    <p class="error"><?php echo htmlspecialchars($errors['insertBlood'], ENT_QUOTES, 'UTF-8'); ?></p>
                   <?php endif; ?>  
                </div> 
                <div>
                    <div class="label">
                        <label class="insertOption">既婚</label>
                    </div>
                    <div>
                        <label><input type="radio" name="insertMarried" value="1" <?php echo htmlspecialchars((isset($_POST['insertMarried'])), ENT_QUOTES, 'UTF-8') ? 'checked' : 'null' ?>>既婚</label>
                    </div>
                </div>

            <!-- 登録ボタン -->
            <input class="insert-btn" type="submit" value="登録" name="insert">

        </form>
    </div>
</body>

</html>