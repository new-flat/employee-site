<?php

require_once __DIR__ . '/../pages/header.php';

$pdo = new PDO(
    'mysql:host=localhost;dbname=php-test',
    'root',
    'root',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// 資格マスタから資格リストを取得
$qualificationList = $pdo->query("SELECT * FROM qualification ORDER BY id ASC")->fetchAll();

// トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pdo->beginTransaction();

    try {
        // 既存の資格情報を更新または削除
        foreach ($_POST['qualification'] as $id => $quali_name) {
            if (!empty($quali_name)) {
                $updateStmt = $pdo->prepare("UPDATE `qualification` SET `quali_name` = :quali_name WHERE `id` = :id");
                $updateStmt->execute([':quali_name' => $quali_name, ':id' => $id]);
            } else {
                // 資格名が空白の場合、その資格を削除
                $deleteStmt = $pdo->prepare("DELETE FROM `qualification` WHERE `id` = :id");
                $deleteStmt->execute([':id' => $id]);
                // IDを再割り当てする
                $pdo->exec("SET @i = 0; UPDATE qualification SET id = (@i := @i +1); ALTER TABLE qualification AUTO_INCREMENT = 1;");
            }
        }

        // 新しい資格の登録
        if (!empty($_POST['new_quali'])) {
            $insertStmt = $pdo->prepare("INSERT INTO `qualification` (`quali_name`) VALUES (:quali_name)");
            $insertStmt->execute([':quali_name' => $_POST['new_quali']]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo $e->getMessage();
    }

    // 成功したら同じページ（quali_masta.php）に戻り、URLを追加してメッセージを表示
    header("Location:/php_lesson/pages/quali_masta.php?success=3");
    exit;
}    

?>
