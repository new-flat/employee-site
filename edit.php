<?php


require_once("header.php"); // セッション開始とCSRFトークン生成

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

        if ($user->email === "0") {
            $user->email = null;
        }

        if (!$user) {
            $errors['id'] = $error_message5;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    $errors['id'] = $error_message5;
}

// ↓これは何？
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
    <header id="menu-title" class="wrapper">
        <div class="title-name">社員編集</div>
    </header>
    <div id="main" class="wrapper">
        <?php if (isset($_GET['success']) && $_GET['success'] == 2) : ?>
            <p style="margin:0">更新しました</p>
        <?php endif ?>

        <?php if (isset($errors['id'])) : ?>
            <p style="margin:0"><?php echo htmlspecialchars($errors['id'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php else : ?>
            <form action="edit_send2.php" method="POST" class="edit-class">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <div>
                    <div class="label">
                        <label class="insertLabel">氏名</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editName" value="<?php echo htmlspecialchars($errors['data']['editName'] ?? $user->username ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if (!empty($errors['messages']['editName'])) : ?>
                        <p><?php echo htmlspecialchars($errors['messages']['editName'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="label">
                        <label class="insertLabel">かな</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editKana" value="<?php echo htmlspecialchars($errors['data']['editKana'] ?? $user->kana ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if (!empty($errors['messages']['editKana'])) : ?>
                        <p class="error"><?php echo htmlspecialchars($errors['messages']['editKana'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">性別</label>
                    </div>
                    <select name="editGender">
                        <option value="1" <?php if (($errors['data']['editGender'] ?? $user->gender) == 1) {
                                                echo "selected";
                                            }; ?>>男</option>
                        <option value="2" <?php if (($errors['data']['editGender'] ?? $user->gender) == 2) {
                                                echo "selected";
                                            }; ?>>女</option>
                        <option value="" <?php if (($errors['data']['editGender'] ?? $user->gender) === null) {
                                                echo "selected";
                                            }; ?>>不明</option>
                    </select>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">生年月日</label>
                    </div>
                    <input type="date" name="editDate" value="<?php echo htmlspecialchars($errors['data']['editDate'] ?? $user->birth_date ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">メールアドレス</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editEmail" value="<?php echo htmlspecialchars($errors['data']['editEmail'] ?? $user->email ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if (!empty($errors['messages']['editEmail'])) : ?>
                        <p><?php echo htmlspecialchars($errors['messages']['editEmail'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                    <?php if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editEmail'])) {
                        $editEmail = $_POST['editEmail'];
                            if (!filter_var($editEmail, FILTER_VALIDATE_EMAIL)) {
                                echo htmlspecialchars("メールアドレスが正しくありません", ENT_QUOTES, 'UTF-8');
                            }
                        }
                    ?>
                </div>
                <div>
                   <div class="label">
                        <label class="insertOption">通勤時間(分)</label>
                   </div>
                   <input type="text" name="editCommute" value="<?php echo htmlspecialchars($errors['data']['editCommute'] ?? $user->commute_time ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                   <?php if (!empty($errors['messages']['editCommute'])) : ?>
                        <p><?php echo htmlspecialchars($errors['messages']['editCommute'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">血液型</label>
                    </div>
                    <div>
                        <label><input type="radio" name="editBlood" value="A" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "A") {
                                            echo "checked";}; ?>> A型</label>
                        <label><input type="radio" name="editBlood" value="B" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "B") {
                                            echo "checked";}; ?>> B型</label>
                        <label><input type="radio" name="editBlood" value="O" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "O") {
                                            echo "checked";}; ?>> O型</label>
                        <label><input type="radio" name="editBlood" value="AB" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "AB") {
                                            echo "checked";}; ?>> AB型</label>
                        <label><input type="radio" name="editBlood" value="" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "") {
                                            echo "checked";}; ?>> 不明</label>
                    </div>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">既婚</label>
                    </div>
                    <div>
                        <label><input type="radio" name="editMarried" value="1" <?php if (($errors['data']['editMarried'] ?? $user->married) == 1) {
                                            echo "checked";}; ?>>既婚</label>
                    </div>
                </div>
                <!-- 保存ボタン -->
                <input class="edit-submit" type="submit" value="保存" name="edit">
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
