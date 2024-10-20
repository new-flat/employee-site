<?php

require_once 'branch_function.php';
require_once 'error_message.php';
require_once 'pdo_connect.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成

$errors = array();

// CSRF対策用トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("リクエストが無効です");
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
    die("リクエストが無効です");
}

// トークンが一致するか確認
// ⏬なぜhash_equalsを使うのか？
// 通常の比較演算子(`==`や`===`)を使うと、文字列の長さや部分的な一致によって処理時間が変わることがあり、
// これを攻撃者が悪用して文字列を推測するリスクがあります。hash_equalsは文字列の全体を一度に比較し、
// 常に一定の時間をかけるため、タイミング攻撃を防止するのに適しています。
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    // トークンが一致しない場合
    die("リクエストが無効です");
}

// 入力データをセッションに保存
$_SESSION['form_data'] = $_POST;
$insertBranch = $_POST['insertBranch'];
$insertTel = $_POST['insertTel'];
$insertPrefecture = $_POST['insertPrefecture'];
$insertCity = $_POST['insertCity'];
$insertAddress = $_POST['insertAddress'];
$insertBuild = isset($_POST['insertBuilding']) ? $_POST['insertBuilding'] : null;
$insertId = $_POST['insertId'];

// 必須項目のチェック
if (empty($insertBranch)) {
    $errors['insertBranch'] = $errorMessage9;
}
if (empty($insertTel)) {
    $errors['insertTel'] = $errorMessage10;
}
if (empty($insertPrefecture)) {
    $errors['insertPrefecture'] = $errorMessage11;
}
if (empty($insertId)) {
    $errors['insertId'] = $errorMessage12;
}

// エラーがある場合はフォームに戻る
if (isset($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location:/employee_site/pages/insert_branch.php');
    exit();
}

try {
    // DB接続
    $pdo = getPdoConnection();

    $sql = "INSERT INTO `branch`(`id`, `branch_name`, `tel`, `prefecture`, `city`, `address`, `building`) 
            VALUES (:id, :branch_name, :tel, :prefecture, :city, :address, :building)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':branch_name', $insertBranch, PDO::PARAM_STR);
    $stmt->bindValue(':tel', $insertTel, PDO::PARAM_STR);
    $stmt->bindValue(':prefecture', $insertPrefecture, PDO::PARAM_INT);
    $stmt->bindValue(':city', $insertCity, PDO::PARAM_STR);
    $stmt->bindValue(':address', $insertAddress, PDO::PARAM_STR);
    $stmt->bindValue(':building', $insertBuild, PDO::PARAM_STR);
    $stmt->bindValue(':id', $insertId, PDO::PARAM_INT);

    $stmt->execute();

    // 成功メッセージと共にリダイレクト
    unset($_SESSION['form_data']);
    header('Location: /employee_site/pages/insert_branch.php?success=1');
    exit();
} catch (PDOException $e) {
    echo $e->getMessage();
    exit();
}

$pdo = null;



