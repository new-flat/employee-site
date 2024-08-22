<?php

require_once 'header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../controll/employee_controll.php';
require_once __DIR__ . '/../controll/error_message.php';
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/editE_controll.php';
require_once __DIR__ . '/../controll/quali_controll.php';
require_once __DIR__ . '/../controll/branch_function.php';

// 社員編集
$errors = array();
$user = null;
if (isset($_GET["id"])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root");
        $edit_sql = "SELECT * FROM `employee` WHERE id = :id";
        $edit_stmt = $pdo->prepare($edit_sql);
        $edit_stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $edit_stmt->execute();
        $user = $edit_stmt->fetch(PDO::FETCH_OBJ); // 1件のデータを取得

        $employeeId = $user->id; // ここで社員IDを取得

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
    $errors['id'] = "URLが間違っています";
}

if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
}

// 保有資格クエリ作成
$qualiIdSql = "SELECT quali_id FROM emp_quali WHERE employee_id = :employee_id";
$stmt = $pdo->prepare($qualiIdSql);
$stmt->bindValue(':employee_id', $_GET["id"], PDO::PARAM_INT);
$stmt->execute();

// 資格IDを配列に格納
$quali_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員編集</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>

<body>
    <div id="main" class="wrapper">
        <div id="menu-title" class="wrapper">
            <h1 class="title-name">社員編集</h1>
        </div>
        <?php if (isset($_GET['success']) && $_GET['success'] == 2) : ?>
            <p style="margin:0">更新しました</p>
        <?php endif ?>

        <?php if (isset($errors['id'])) : ?>
            <p style="margin:0"><?php echo eh($errors['id']); ?></p>
        <?php else : ?>
            <form action="/php_lesson/controll/editE_controll.php" method="POST" class="edit-class">
                <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="id" value="<?php echo eh($_GET['id']); ?>">
                <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employeeId); ?>">
                <div>
                    <div class="label">
                        <label class="insertLabel">氏名</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editName" value="<?php echo eh($errors['data']['editName'] ?? $user->username ?? ''); ?>">
                    <?php if (!empty($errors['messages']['editName'])) : ?>
                        <p><?php echo eh($errors['messages']['editName']); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="label">
                        <label class="insertLabel">かな</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editKana" value="<?php echo eh($errors['data']['editKana'] ?? $user->kana ?? ''); ?>">
                    <?php if (!empty($errors['messages']['editKana'])) : ?>
                        <p class="error"><?php echo eh($errors['messages']['editKana']); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="label">
                        <label class="insertLabel">部門</label> 
                    </div>
                    <select name="editBranch">
                        <option value="">支店を選択</option>
                        <?php foreach ($branches as $key => $branch) : ?>
                            <option value="<?php echo eh($key); ?>" <?php echo $user->branch == $key ? 'selected' : ''; ?>>
                                <?php echo eh($branch); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['messages']['editBranch'])) : ?>
                        <p class="error"><?php echo eh($errors['messages']['editBranch']); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">性別</label>
                    </div>
                    <select name="editGender">
                        <option value="1" <?php if (($errors['data']['editGender'] ?? $user->gender) == 1) {
                            echo "selected";
                        } ?>>男</option>
                        <option value="2" <?php if (($errors['data']['editGender'] ?? $user->gender) == 2) {
                            echo "selected";
                        } ?>>女</option>
                        <option value="" <?php if (($errors['data']['editGender'] ?? $user->gender) === null) {
                            echo "selected";
                        } ?>>不明</option>
                    </select>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">生年月日</label>
                    </div>
                    <input type="date" name="editDate" value="<?php echo eh($errors['data']['editDate'] ?? $user->birth_date ?? ''); ?>">
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">メールアドレス</label>
                        <p class="insertMust">必須</p>
                    </div>
                    <input type="text" name="editEmail" value="<?php echo eh($errors['data']['editEmail'] ?? $user->email ?? ''); ?>">
                    <?php if (!empty($errors['messages']['editEmail'])) : ?>
                        <p><?php echo eh($errors['messages']['editEmail']); ?></p>
                    <?php endif; ?>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editEmail'])) {
                        $editEmail = $_POST['editEmail'];
                        if (!filter_var($editEmail, FILTER_VALIDATE_EMAIL)) {
                            echo eh("メールアドレスが正しくありません");
                        }
                    }
                    ?>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">パスワード</label>
                        <p>変更する場合のみ入力</p>
                    </div>
                    <input type="password" name="editPass" value="">
                    <?php if (!empty($errors['editPass'])) : ?>
                        <p class="error"><?php echo eh($errors['editPass']); ?></p>
                    <?php endif; ?>    
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">通勤時間(分)</label>
                    </div>
                    <input type="text" name="editCommute" value="<?php echo eh($errors['data']['editCommute'] ?? $user->commute_time ?? ''); ?>">
                    <?php if (!empty($errors['messages']['editCommute'])) : ?>
                        <p><?php echo eh($errors['messages']['editCommute'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">血液型</label>
                    </div>
                    <div>
                        <label><input type="radio" name="editBlood" value="A" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "A") {
                            echo "checked";
                        } ?>> A型</label>
                        <label><input type="radio" name="editBlood" value="B" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "B") {
                            echo "checked";
                        } ?>> B型</label>
                        <label><input type="radio" name="editBlood" value="O" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "O") {
                            echo "checked";
                        } ?>> O型</label>
                        <label><input type="radio" name="editBlood" value="AB" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "AB") {
                            echo "checked";
                        } ?>> AB型</label>
                        <label><input type="radio" name="editBlood" value="" <?php if (($errors['data']['editBlood'] ?? $user->blood_type) == "") {
                            echo "checked";
                        } ?>> 不明</label>
                    </div>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">既婚</label>
                    </div>
                    <div>
                        <label><input type="radio" name="editMarried" value="1" <?php if (($errors['data']['editMarried'] ?? $user->married) == 1) {
                            echo "checked";
                        } ?>>既婚</label>
                    </div>
                </div>
                <div>
                    <div class="label">
                        <label class="insertOption">保有資格</label>
                    </div>
                    <div>
                        <?php foreach ($qualificationList as $qualification) 
                        {
                           echo '<input type="checkbox" name="editQuali[]" value="'.$qualification['id'].'"';
                           if (in_array($qualification['id'], $quali_ids)) 
                           {
                                echo ' checked';
                           }
                           echo'>' . $qualification['quali_name'] . ' ';
                        }
                        ?>
                    </div>
                </div>
                <!-- 保存ボタン -->
                <input class="edit-submit" type="submit" value="保存" name="edit">

            </form>
            <form action="/php_lesson/controll/delete.php" method="POST" class="edit-class" onsubmit="return confirmDelete();">
                <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="delete" value="<?php echo eh($_GET['id']); ?>">
                <button type="submit" class="edit-btn">削除</button>
            </form>

            <script>
            function confirmDelete() {
                return confirm("本当に削除しますか？");
            }
            </script>
        <?php endif; ?>
    </div>
</body>

</html>
