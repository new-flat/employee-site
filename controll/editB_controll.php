<?php
require_once 'branch_controll.php';
require_once 'error_message.php';
require_once 'pdo_connect.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成

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
$originalId = $_POST['originalId'] ?? null;
$newId = $_POST['editId'] ?? null;
$editBranch = $_POST['editBranch'];
$editTel = $_POST['editTel'];
$editPrefecture = $_POST['editPrefecture'];
$editCity = $_POST['editCity'];
$editAddress = $_POST['editAddress'];
$editBuilding = $_POST['editBuild'];
$errors = array();

// 必須項目のチェック
if (empty($editBranch)) {
    $errors['editBranch'] = $errorMessage9;
}
if (empty($editTel)) {
    $errors['editTel'] = $errorMessage10;
}
if (empty($editPrefecture)) {
    $errors['editPrefecture'] = $errorMessage11;
}
if (empty($newId)) {
    $errors['editId'] = $errorMessage12; 
}

// 編集データをDBに登録
if (!empty($errors)) {
    // エラーがある場合はフォームに戻る
    $errorData = [
        'editBranch' => $editBranch,
        'editTel' => $editTel,
        'editPrefecture' => $editPrefecture,
        'editCity' => $editCity,
        'editAddress' => $editAddress,
        'editBuild' => $editBuilding,
        'editId' => $newId
    ];

    $errorQuery = http_build_query(['errors' => json_encode(['messages' => $errors, 'data' => $errorData])]);
    header("Location:/employee_site/pages/edit_branch.php?id=" . $originalId . "&" . $errorQuery);
    exit;
}

try {
    // DB接続
    $pdo = getPdoConnection();

    $pdo->beginTransaction();

    // 並び順が変更される場合、$id_update_sqlで先にIDを変更する
    if ($originalId !== $newId) {
        $idSql = "UPDATE `branch` SET id = :new_id WHERE id = :original_id";
        $idStmt = $pdo->prepare($idSql);
        $idStmt->bindValue(':new_id', $newId, PDO::PARAM_INT);
        $idStmt->bindValue(':original_id', $originalId, PDO::PARAM_INT);
        $idStmt->execute();
    }

    // データの更新
    $updateSql = "UPDATE `branch` SET branch_name = :branch_name, tel = :tel, prefecture = :prefecture, city = :city, address = :address, building = :building WHERE id = :id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindValue(':branch_name', $editBranch, PDO::PARAM_STR);
    $updateStmt->bindValue(':tel', $editTel, PDO::PARAM_STR);
    $updateStmt->bindValue(':prefecture', $editPrefecture, PDO::PARAM_INT);
    $updateStmt->bindValue(':city', $editCity, PDO::PARAM_STR);
    $updateStmt->bindValue(':address', $editAddress, PDO::PARAM_STR);
    $updateStmt->bindValue(':building', $editBuilding, PDO::PARAM_STR);
    $updateStmt->bindValue(':id', $newId, PDO::PARAM_INT); // 新しいIDで更新
    $updateStmt->execute();

    $pdo->commit();
    // 成功したら同じページ（edit_branch.php）に戻り、URLを追加してメッセージを表示
    header("Location:/employee_site/pages/edit_branch.php?id=" . $newId . "&success=1");
    // リダイレクト後に不要なコードが実行されないようにする
    exit;

} catch (PDOException $e) {
    echo $e->getMessage();
}

$pdo = null;
?>
