<!-- 社員データ登録画面 -->

<?php
require_once 'header.php';
require_once __DIR__ . '/../controll/employee_controll.php';
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/quali_controll.php';
require_once __DIR__ . '/../controll/branch_function.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員登録</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>

<body>
    <div id="menu-title" class="wrapper">
        <div class="title-name">
            <a class="menu_btn" href="#">社員登録</a>
        </div>
    </div>
    <div id="main" class="wrapper">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p style="margin:0"><?php echo eh("登録しました"); ?></p>
        <?php endif; ?>

        <form action="/php_lesson/controll/insertE_controll.php" method='POST' class="insert-class">
            <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
            <div>
                <div class="label">
                    <label class="insertLabel">氏名</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertName" required value="<?php echo eh($_POST['insertName'] ?? ''); ?>">
                <?php if (!empty($errors['insertName'])): ?>
                    <p><?php echo eh($errors['insertName']); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <div class="label">
                    <label class="insertLabel">かな</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertKana" required value="<?php echo eh($_POST['insertKana'] ?? ''); ?>">
                <?php if (!empty($errors['insertKana'])): ?>
                    <p class="error"><?php echo eh($errors['insertKana']); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <div class="label">
                    <label class="insertLabel">部門</label> 
                </div>
                <select name="insertBranch">
                    <option value="">支店を選択</option>
                    <?php foreach ($branches as $key => $branch): ?>
                        <option value="<?php echo eh($key); ?>" <?php echo (isset($_POST['insertBranch']) && $_POST['insertBranch'] === $key) ? 'selected' : ''; ?>><?php echo eh($branch); ?></option>
                    <?php endforeach; ?>
                    <?php if (!empty($errors['insertBranch'])): ?>
                        <p><?php echo eh($errors['insertBranch']); ?></p>
                    <?php endif; ?>
                </select>
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
                <input type="date" name="insertDate" value="<?php echo eh($_POST['insertDate'] ?? ''); ?>">
            </div>
            <div>
                <div class="label">
                    <label class="insertOption">メールアドレス</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="text" name="insertEmail" value="<?php echo eh($errors['data']['insertEmail']  ?? ''); ?>">
                <?php if (!empty($errors['insertEmail'])): ?>
                    <p class="error"><?php echo eh($errors['insertEmail']); ?></p>
                <?php endif; ?>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertEmail'])) {
                    $insertEmail = $_POST['insertEmail'];
                    if (!filter_var($insertEmail, FILTER_VALIDATE_EMAIL)) {
                        echo eh('メールアドレスが正しくありません');
                    }
                }
                ?>
            </div>
            <div>
                <div class="label">
                    <label class="insertOption">パスワード</label>
                    <p class="insertMust">必須</p>
                </div>
                <input type="password" name="insertPass" required value="<?php echo eh($errors['data']['insertPass'] ?? ''); ?>">
                <?php if (!empty($errors['insertPass'])): ?>
                    <p class="error"><?php echo eh($errors['insertPass']); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <div class="label">
                    <label class="insertOption">通勤時間(分)</label>
                </div>
                <input type="type" name="insertCommute" value="<?php echo eh($errors['data']['insertCommute'] ?? ''); ?>">
                <?php if (!empty($errors['insertCommute'])): ?>
                    <p class="error"><?php echo eh($errors['insertCommute']); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <div class="label">
                    <label class="insertOption">血液型</label>
                </div>
                <div>
                    <label><input type="radio" name="insertBlood" value="A" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === 'A') ? 'checked' : ''; ?>> A型</label>
                    <label><input type="radio" name="insertBlood" value="B" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === 'B') ? 'checked' : ''; ?>> B型</label>
                    <label><input type="radio" name="insertBlood" value="O" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === 'O') ? 'checked' : ''; ?>> O型</label>
                    <label><input type="radio" name="insertBlood" value="AB" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === 'AB') ? 'checked' : ''; ?>> AB型</label>
                    <label><input type="radio" name="insertBlood" value="" <?php echo (isset($_POST['insertBlood']) && $_POST['insertBlood'] === '') ? 'checked' : ''; ?>> 不明</label>
                </div>
                <?php if (!empty($errors['insertBlood'])): ?>
                    <p class="error"><?php echo eh($errors['insertBlood']); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <div class="label">
                    <label class="insertOption">既婚</label>
                </div>
                <div>
                    <label><input type="radio" name="insertMarried" value="1" <?php echo (isset($_POST['insertMarried'])) ? 'checked' : 'null'; ?>>既婚</label>
                </div>
            </div>
            <div>
                <div class="label">
                    <label class="insertOption">保有資格</label>
                </div>
                <div>

                    <?php foreach ($qualificationList as $qualiId => $quali) : ?>
                        <input type="checkbox" name="insertQuali[]" value="<?php echo eh($qualiId); ?>" 
                            <?php echo (isset($_POST['insertQuali']) && $_POST['insertQuali'] === $qualiId) ? 'selected' : ''; ?>>
                        <label for=""><?php echo eh($quali["quali_name"]); ?></label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 登録ボタン -->
            <input class="insert-btn" type="submit" value="登録" name="insert">

            <p>すでに登録済みの方は<a href="login_form.php">こちら</a></p>

        </form>
    </div>
</body>

</html>
