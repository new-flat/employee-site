<?php  

require_once 'employee_controll.php';

$delete = $_POST['delete'];

$pdo = new PDO(
    'mysql:host=localhost;dbname=php-test',
    'root',
    'root',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
$deleteSql = "UPDATE `php-test` SET is_deleted = 1 WHERE id = :id";
$deleteStmt = $pdo->prepare($deleteSql);
$deleteStmt->bindValue(':id', $delete, PDO::PARAM_INT);
if ($deleteStmt->execute()) {
    // 削除成功時、社員一覧ページにリダイレクトし、メッセージを表示
    header("Location:/php_lesson/pages/employee_list.php?message=削除しました");
    exit();
} else {
    echo "エラーが発生しました: " . $deleteStmt->error;
}

?>