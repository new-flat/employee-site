<?php
require_once 'header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../controll/get_employee.php';
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/not_login.php';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員編集</title>
    <link rel="stylesheet" href='/employee_site/css/style.css'>
</head>

<body>
    <div id="main" class="wrapper">
        <div id="menu-title">
            <h1 class="title-name">社員編集</h1>
        </div>

        <!-- URLが間違っていればエラー文 -->
        <?php if (isset($errors['id'])) : ?>
            <p style="margin:0"><?php echo eh($errors['id']); ?></p>
        <?php else : ?>
            <form action="/employee_site/controll/editE_controll.php" method="POST" class="edit-class">
                <!-- 成功メッセージ -->
                <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
                    <p class="success-message">更新しました</p>
                <?php endif ?>

                <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="id" value="<?php echo eh($_GET['id']); ?>">
                <input type="hidden" name="employee_id" value="<?php echo eh($employeeId); ?>">

                <?php $errors = isset($_GET['errors']) ? json_decode($_GET['errors'], true) : []; ?>

                <!-- 氏名 -->
                <div>
                    <div class="label">
                        <label class="insertLabel">氏名</label>
                        <p class="insert-must">必須</p>
                    </div>
                    <input type="text" name="editName" value="<?php echo eh($errors['data']['editName'] ?? $user->username ?? ''); ?>">
                    <!-- エラー文 -->
                    <?php if (!empty($errors['messages']['editName'])) : ?>
                        <p><?php echo eh($errors['messages']['editName']); ?></p>
                    <?php endif; ?>
                </div>

                <!-- かな -->
                <div>
                    <div class="label">
                        <label class="insertLabel">かな</label>
                        <p class="insert-must">必須</p>
                    </div>
                    <input type="text" name="editKana" value="<?php echo eh($errors['data']['editKana'] ?? $user->kana ?? ''); ?>">
                    <!-- エラー文 -->
                    <?php if (!empty($errors['messages']['editKana'])) : ?>
                        <p class="error"><?php echo eh($errors['messages']['editKana']); ?></p>
                    <?php endif; ?>
                </div>

                <!-- 部門 -->
                <div>
                    <div class="label">
                        <label class="insertLabel">部門</label> 
                    </div>
                    <select name="editBranch">
                        <option value="">支店を選択</option>
                        <?php foreach ($branches as $key => $branch) : ?>
                            <option value="<?php echo eh($key); ?>"<?php echo ($user->branch ?? '') == $key ? 'selected' : ''; ?>>
                                <?php echo eh($branch); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- エラー文 -->
                    <?php if (!empty($errors['messages']['editBranch'])) : ?>
                        <p class="error"><?php echo eh($errors['messages']['editBranch']); ?></p>
                    <?php endif; ?>
                </div>

                <!-- 性別 -->
                <div>
                    <div class="label">
                        <label class="insertOption">性別</label>
                    </div>
                    <select name="editGender">
                        <option value="1" <?php if (($errors['data']['editGender'] ?? $user->gender ?? '') == 1) {
                            echo "selected";
                        } ?>>男</option>
                        <option value="2" <?php if (($errors['data']['editGender'] ?? $user->gender ?? '') == 2) {
                            echo "selected";
                        } ?>>女</option>
                        <option value="" <?php if (($errors['data']['editGender'] ?? $user->gender ?? '') === null) {
                            echo "selected";
                        } ?>>不明</option>
                    </select>
                </div>

                <!-- 生年月日 -->
                <div>
                    <div class="label">
                        <label class="insertOption">生年月日</label>
                    </div>
                    <input type="date" name="editDate" value="<?php echo eh($errors['data']['editDate'] ?? $user->birth_date ?? ''); ?>">
                </div>

                <!-- メールアドレス -->
                <div>
                    <div class="label">
                        <label class="insertOption">メールアドレス</label>
                        <p class="insert-must">必須</p>
                    </div>
                    <input type="text" name="editEmail" value="<?php echo eh($errors['data']['editEmail'] ?? $user->email ?? ''); ?>">
                    <?php if (!empty($errors['messages']['editEmail'])) : ?>
                        <p class="error"><?php echo eh($errors['messages']['editEmail']); ?></p>
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

                <!-- パスワード -->
                <div>
                    <div class="label">
                        <label class="insertOption">パスワード</label>
                        <p>変更する場合のみ入力</p>
                    </div>
                    <input type="password" name="editPass" value="<?php echo eh($errors['data']['editPass'] ?? $user->password ?? '') ?>">
                    <?php if (!empty($errors['editPass'])) : ?>
                        <p class="error">
                            <?php echo eh($errors['messages']['editPass']); ?>
                        </p>
                    <?php endif; ?>    
                </div>

                <!-- 通勤時間 -->
                <div>
                    <div class="label">
                        <label class="insertOption">通勤時間(分)</label>
                    </div>
                    <input type="text" name="editCommute" value="<?php echo eh($errors['data']['editCommute'] ?? $user->commute_time ?? ''); ?>">
                    <?php if (!empty($errors['messages']['editCommute'])) : ?>
                        <p class="error">
                            <?php echo eh($errors['messages']['editCommute']); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- 血液型 -->
                <div>
                    <div class="label">
                        <label class="insertOption">血液型</label>
                    </div>
                    <div>
                    <?php
                        foreach ($blood_types as $value => $label) {
                            $checked = ($selected_blood == $value) ? "checked" : "";
                            echo "<label><input type='radio' name='editBlood' value='{$value}' {$checked}> {$label}</label>";
                        }
                    ?>
                    </div>
                    <?php if (!empty($errors['messages']['editBlood'])): ?>
                        <p class="error">
                            <?php echo eh($errors['messages']['editBlood']); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- 既婚か独身か -->
                <div>
                    <div class="label">
                        <label class="insertOption">既婚</label>
                    </div>
                    <div>
                        <label><input type="radio" name="editMarried" value="1" <?php if (($errors['data']['editMarried'] ?? $user->married ?? '') == 1) {
                            echo "checked";
                        } ?>>既婚</label>
                    </div>
                </div>

                <!-- 保有資格 -->
                <div>
                    <div class="label">
                        <label class="insertOption">保有資格</label>
                    </div>
                    <div>
                        <?php foreach ($qualificationList as $qualification) 
                        {
                            // 資格名ID
                           echo '<input type="checkbox" name="editQuali[]" value="'.$qualification->id.'"';
                           if (in_array($qualification->id, $qualiIds)) 
                           {
                                echo ' checked';
                           }
                           echo'>' . $qualification->quali_name . ' ';
                        }
                        ?>
                    </div>
                </div>

                <!-- 保存ボタン -->
                <input class="submit-btn" type="submit" value="保存" name="edit">

            </form>
            <form action="/employee_site/controll/delete.php" method="POST" class="edit-class" onsubmit="return confirmDelete();">
                <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="delete" value="<?php echo eh($_GET['id']); ?>">
                <button type="submit" class="submit-btn">削除</button>
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
