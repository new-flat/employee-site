<?php

require_once __DIR__ . '/../pages/header.php';
require_once 'pdo_connect.php';

$pdo = getPdoConnection();

// トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("リクエストが無効です");
}   

try {
    // 既存の資格情報を更新または削除
    foreach ($_POST['qualification'] as $id => $qualiName) {
        if (trim($qualiName) !== '') {
            $updateStmt = $pdo->prepare("UPDATE `qualification` SET `quali_name` = :quali_name WHERE `id` = :id");
            $updateStmt->execute([':quali_name' => $qualiName, ':id' => $id]);
        } else {
            // まず関連する資格データを削除
            $deleteEmpQualiStmt = $pdo->prepare("DELETE FROM `emp_quali` WHERE `quali_id` = :id");
            $deleteEmpQualiStmt->execute([':id' => $id]);

            // 資格マスタからも削除
            $deleteStmt = $pdo->prepare("DELETE FROM `qualification` WHERE `id` = :id");
            $deleteStmt->execute([':id' => $id]);
        }
    }

    // IDの再割り当て
    $pdo->exec("SET @i = 0;");
    $pdo->exec("UPDATE qualification SET id = (@i := @i +1);");
    $pdo->exec("ALTER TABLE qualification AUTO_INCREMENT = 1;");

    // 新しい資格の登録
    if (!empty($_POST['new_quali'])) {
        $insertStmt = $pdo->prepare("INSERT INTO `qualification` (`quali_name`) VALUES (:quali_name)");
        $insertStmt->execute([':quali_name' => $_POST['new_quali']]);
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // エラーメッセージを出力
    echo "Error: " . $e->getMessage();
    exit;  
}

// 成功したら同じページ（quali_masta.php）に戻り、URLを追加してメッセージを表示
header("Location:/employee_site/pages/quali_masta.php?success=3");
exit;

?>
