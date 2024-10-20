<?php  

require_once 'employee_controll.php';
require_once 'pdo_connect.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成

// トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("リクエストが無効です");
}
// トークンを生成
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
    die('リクエストが無効です');
}
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("リクエストが無効です");
}

// フォームの入力値を取得
$delete = $_POST['delete'];

try{
    // DB接続
    $pdo = getPdoConnection();
    $deleteSql = "UPDATE `employee` SET is_deleted = 1 WHERE id = :id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindValue(':id', $delete, PDO::PARAM_INT);
    if ($deleteStmt->execute()) {
        // 削除成功時、社員一覧ページにリダイレクトし、メッセージを表示
        header("Location:/employee_site/pages/employee_list.php?message=削除しました");
        exit();
    } else {
        echo "エラーが発生しました: " . $deleteStmt->$error;
    }            
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

?>