<?php
require_once 'pdo_connect.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../pages/profile_view.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("リクエストが無効です");
}
    
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("リクエストが無効です");
}

// フォームの入力値を取得
$introText = $_POST['introText'] ?? null;
$profId = $_POST['id'] ?? null;

// プロフィール画像がアップロードされた場合
if (isset($_FILES['profImage']) && $_FILES['profImage']['error'] === UPLOAD_ERR_OK) { //ファイルが正常にアップロードされているか
    // 許可されるファイルタイプ
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($_FILES['profImage']['tmp_name']);

    //ファイルタイプの確認
    if (!in_array($fileType, $allowedTypes)) {
        echo "許可されていないファイル形式です。";
        exit;
    }

    // アップロードされたファイルの拡張子を取得
    $ext = pathinfo($_FILES['profImage']['name'], PATHINFO_EXTENSION);

    // 新しいファイル名を生成
    $newFileName = uniqid() . '.' . $ext;

    // 画像ファイルを指定したディレクトリに保存
    $uploadDir = __DIR__ . '/images/';
    if (move_uploaded_file($_FILES['profImage']['tmp_name'], $uploadDir . $newFileName)) {
        $profImage = $newFileName;
    } else {
        echo "ファイルの保存に失敗しました。";
        exit;
    }
} elseif (isset($_POST['deleteImage']) && $_POST['deleteImage'] == 1) {
    // 画像を「削除する」がチェックされた場合
    $profImage = null;
} else {
    // どちらの操作も行われなかった場合
    $profImage = $user['image'];
}

try {
    $pdo = getPdoConnection();

    // データ更新
    $sql = "UPDATE employee SET image = :image, intro_text = :intro_text 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':image', $profImage);
    $stmt->bindValue(':intro_text', $introText);
    $stmt->bindValue(':id', $profId);

    if ($stmt->execute()) {
        header("Location:/employee_site/pages/profile_view.php?id=&success=1");
        exit;
    } else {
        echo '登録に失敗しました';
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

?>