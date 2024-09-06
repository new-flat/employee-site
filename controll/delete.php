<?php  

require_once 'employee_controll.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成

// トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // トークンを生成し、セッションに保存
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        die('リクエストが無効です');
    }
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $delete = $_POST['delete'];

        $pdo = new PDO(
            'mysql:host=localhost;dbname=php-test',
            'root',
            'root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $deleteSql = "UPDATE `employee` SET is_deleted = 1 WHERE id = :id";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindValue(':id', $delete, PDO::PARAM_INT);
        if ($deleteStmt->execute()) {
            // 削除成功時、社員一覧ページにリダイレクトし、メッセージを表示
            header("Location:/php_lesson/pages/employee_list.php?message=削除しました");
            exit();
        } else {
            echo "エラーが発生しました: " . $deleteStmt->error;
        }
    }
}


?>