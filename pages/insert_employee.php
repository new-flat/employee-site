<!-- 社員データ登録画面 -->
<?php
require_once 'header.php';
require_once __DIR__ . '/../controll/employee_controll.php';
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/not_login.php';
require_once __DIR__ . '/../controll/pdo_connect.php';

$pdo = getPdoConnection();

// 資格マスタから資格リストを取得
$qualificationList = $pdo->query("SELECT * FROM qualification ORDER BY id ASC")->fetchAll();

// 血液型定義
$bloodTypes = ["A" => "A型", "B" => "B型", "O" => "O型", "AB" => "AB型", "" => "不明"];
// 選択された血液型を格納
$selectedBlood = $_POST['insertBlood'] ?? '';

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
    <title>社員登録</title>
    <link rel="stylesheet" href='/employee_site/css/style.css'>
</head>

<body>
    <div id="main" class="wrapper">
        <div id="menu-title">
            <h1 class="title-name">社員登録</h1>
        </div>

        <form action="/employee_site/controll/insertE_controll.php" method='POST' class="edit-class">
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <p class="success-message">登録しました</p>
            <?php endif; ?>
            <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">

            <!-- 氏名 -->
            <div>
                <div class="label">
                    <label class="insertLabel">氏名</label>
                    <p class="insert-must">必須</p>
                </div>
                <input type="text" name="insertName" value="<?php echo eh(isset($_POST['insertName']) ? ($_POST['insertName']) : ''); ?>">
                <?php if (!empty($errors['insertName'])): ?>
                    <p class="error-message"><?php echo eh($errors['insertName']); ?></p>
                <?php endif; ?>
            </div>

            <!-- かな -->
            <div>
                <div class="label">
                    <label class="insertLabel">かな</label>
                    <p class="insert-must">必須</p>
                </div>
                <input type="text" name="insertKana" value="<?php echo eh($_POST['insertKana'] ?? ''); ?>">
                <?php if (!empty($errors['insertKana'])): ?>
                    <p class="error-message"><?php echo eh($errors['insertKana']); ?></p>
                <?php endif; ?>
            </div>

            <!-- 部門 -->
            <div>
                <div class="label">
                    <label class="insertLabel">部門</label> 
                </div>
                <select name="insertBranch">
                    <option value="">支店を選択</option>
                    <?php foreach ($branches as $key => $branch): ?>
                        <option value="<?php echo eh($key); ?>" <?php echo (isset($_POST['insertBranch']) && $_POST['insertBranch'] === $key) ? 'selected' : ''; ?>><?php echo eh($branch); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 氏名 -->
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

            <!-- 生年月日 -->
            <div>
                <div class="label">
                    <label class="insertOption">生年月日</label>
                </div>
                <input type="date" name="insertDate" value="<?php echo eh($_POST['insertDate'] ?? ''); ?>">
            </div>

            <!-- メールアドレス -->
            <div>
                <div class="label">
                    <label class="insertOption">メールアドレス</label>
                    <p class="insert-must">必須</p>
                </div>
                <input type="text" name="insertEmail" value="<?php echo eh($_POST['insertEmail'] ?? ''); ?>">
                <?php if (!empty($errors['insertEmail'])): ?>
                    <p class="error-message"><?php echo eh($errors['insertEmail']); ?></p>
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

            <!-- パスワード -->
            <div>
                <div class="label">
                    <label class="insertOption">パスワード</label>
                    <p class="insert-must">必須</p>
                </div>
                <input type="password" name="insertPass" value="<?php echo eh($_POST['insertPass'] ?? ''); ?>">
                <?php if (!empty($errors['insertPass'])): ?>
                    <p class="error-message"><?php echo eh($errors['insertPass']); ?></p>
                <?php endif; ?>
            </div>

            <!-- 通勤時間 -->
            <div>
                <div class="label">
                    <label class="insertOption">通勤時間(分)</label>
                </div>
                <input type="type" name="insertCommute" value="<?php echo eh($_POST['insertCommute'] ?? ''); ?>">
                <?php if (!empty($errors['insertCommute'])): ?>
                    <p class="error-message"><?php echo eh($errors['insertCommute']); ?></p>
                <?php endif; ?>
            </div>

            <!-- 血液型 -->
            <div>
                <div class="label">
                    <label class="insertOption">血液型</label>
                </div>
                <div>
                <?php
                    foreach ($bloodTypes as $value => $label) {
                        $checked = ($selectedBlood === $value) ? 'checked' : '';
                        echo "<label><input type='radio' name='insertBlood' value='{$value}' {$checked}> {$label}</label>";
                    }
                ?>
                </div>
            </div>

            <!-- 既婚 -->
            <div>
                <div class="label">
                    <label class="insertOption">既婚</label>
                </div>
                <div>
                    <label><input type="radio" name="insertMarried" value="1" <?php echo (isset($_POST['insertMarried'])) ? 'checked' : ''; ?>>既婚</label>
                </div>
            </div>

            <!-- 保有資格 -->
            <div>
                <div class="label">
                    <label class="insertOption">保有資格</label>
                </div>
                <div>
                    <?php foreach ($qualificationList as $qualiId => $quali) : ?>
                        <input type="checkbox" name="insertQuali[]" value="<?php echo eh($qualiId); ?>" 
                            <?php echo (in_array($qualiId, $_POST['insertQuali'] ?? [])) ? 'checked' : ''; ?>>
                        <label for="quali" class="quali-name"><?php echo eh($quali->quali_name); ?></label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 登録ボタン -->
            <input class="submit-btn" type="submit" value="登録">
        </form>
    </div>
</body>

</html>
