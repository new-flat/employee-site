<?php

require_once 'employee_controll.php';
require_once 'error_message.php';
require_once 'pdo_connect.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$editQuali = []; 

// トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("リクエストが無効です");
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
    die('リクエストが無効です');
}

// トークンが一致するか確認
// ⏬なぜhash_equalsを使うのか？
// 通常の比較演算子(`==`や`===`)を使うと、文字列の長さや部分的な一致によって処理時間が変わることがあり、
// これを攻撃者が悪用して文字列を推測するリスクがあります。hash_equalsは文字列の全体を一度に比較し、
// 常に一定の時間をかけるため、タイミング攻撃を防止するのに適しています。
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("リクエストが無効です");
}

// フォームの入力値を取得
$id = $_POST['id'];
$editName = $_POST['editName'];
$editKana = $_POST['editKana'];
$editBranch = $_POST['editBranch'] ?? null;
$editGender = $_POST['editGender'];
$editDate = $_POST['editDate'] ?? '';
$editEmail = $_POST['editEmail'];
$editCommute = isset($_POST['editCommute']) ? trim($_POST['editCommute']) : null;
$editBlood = $_POST['editBlood'] ?? '';
$editMarried = $_POST['editMarried'] ?? null;
$editQuali = $_POST['editQuali'] ?? [];
$editPass = $_POST['editPass'] ?? null;

$errors = [];

// 必須項目のチェック
if (empty($_POST['editName'])) {
    $errors['messages']['editName'] = $errorMessage1;
}
if (empty($_POST['editKana'])) {
    $errors['messages']['editKana'] = $errorMessage2;
}
if (empty($_POST['editPass'])) {
    $errors['messages']['editName'] = $errorMessage3;
}
if (empty($_POST['editEmail'])) {
    $errors['messages']['editEmail'] = $errorMessage6;
}
if (empty($_POST['editBlood'])) {
    $errors['messages']['editBlood'] = $errorMessage7;
}

// パスワードの長さチェック
if (strlen($editPass) < 8) {
    $errors['messages']['editPass'] = $errorMessage13;
} else {
    $hashedPass = password_hash($editPass, PASSWORD_DEFAULT);
}

// 通勤時間のバリデーション（1以上の整数かどうか）
if (!filter_var($editCommute, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
    $errors['messages']['editCommute'] = '1以上の整数を入力してください';
}

// 生年月日が未入力の場合
if ($editDate === '') {
    $editDate = null;
}

if (!empty($errors)) {
    $queryString = http_build_query(['errors' => json_encode($errors)]);
    header("Location:/employee_site/pages/edit_employee.php?id={$id}&{$queryString}");
    exit;
}

// エラーがない場合編集データをDBに登録
try {
    // DB接続
    $pdo = getPdoConnection();

    // トランザクション開始
    $pdo->beginTransaction();
    
    $updateSql = "UPDATE `employee` 
                    SET username = :username, kana = :kana, branch = :branch, gender = :gender, 
                        birth_date = :birth_date, email = :email, password = :password, commute_time = :commute_time, 
                        blood_type = :blood_type, married = :married
                    WHERE id = :id";

    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindValue(':username', $editName);
    $updateStmt->bindValue(':kana', $editKana);
    $updateStmt->bindValue(':branch', $editBranch === '' ? null : $editBranch, PDO::PARAM_INT);
    $updateStmt->bindValue(':gender', $editGender === '' ? null : $editGender, PDO::PARAM_INT);
    $updateStmt->bindValue(':birth_date', $editDate);
    $updateStmt->bindValue(':email', $editEmail);
    $updateStmt->bindValue(':password', $hashedPass, PDO::PARAM_STR);
    $updateStmt->bindValue(':commute_time', $editCommute === '' ? null : $editCommute, PDO::PARAM_INT);
    $updateStmt->bindValue(':blood_type', $editBlood, PDO::PARAM_STR);
    $updateStmt->bindValue(':married', $editMarried === '' ? null : $editMarried, PDO::PARAM_INT);
    $updateStmt->bindValue(':id', $id, PDO::PARAM_INT);
    $updateStmt->execute();

    // 既存の資格データ削除
    $deleteSql = "DELETE FROM emp_quali WHERE employee_id = :employee_id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindValue(':employee_id', $id, PDO::PARAM_INT);
    $deleteStmt->execute();

    // 保有資格が追加・更新された場合、新たな資格データを挿入
    if (!empty($editQuali)) {
        $insertSql = "INSERT INTO emp_quali (employee_id, quali_id) VALUES (:employee_id, :quali_id)";
        $insertStmt = $pdo->prepare($insertSql);

        foreach ($editQuali as $qualiId) {
            $insertStmt->bindValue(':employee_id', $id, PDO::PARAM_INT);
            $insertStmt->bindValue(':quali_id', $qualiId, PDO::PARAM_INT);
            $insertStmt->execute();
        }
    }

    $pdo->commit();

    // 更新成功後、フォームページ（edit_employee.php）に戻り、URLを追加してメッセージを表示
    header("Location:/employee_site/pages/edit_employee.php?id=" . $id . "&success=1");
    exit;

} catch (PDOException $e) {
    echo $e->getMessage();
}

$pdo = null;


