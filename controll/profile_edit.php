<?php

require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../pages/profile_view.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // フォームの入力値を取得
        $introText = $_POST['introText'] ?? null;
        $profId = $_POST['id'] ?? null;

        // プロフィール画像がアップロードされた場合
        if (isset($_FILES['profImage']) && $_FILES['profImage']['error'] === UPLOAD_ERR_OK) { //ファイルが正常にアップロードされているか
            // アップロードされたファイルの拡張子を取得
            $ext = pathinfo($_FILES['profImage']['name'], PATHINFO_EXTENSION);
            // 新しいファイル名を生成
            $newFileName = uniqid() . '.' . $ext;
            // 画像ファイルを指定したディレクトリに保存
            move_uploaded_file($_FILES['profImage']['tmp_name'], '/Applications/MAMP/htdocs/php_lesson/images/' . $newFileName);
            $profImage = $newFileName;
            var_dump(1);
        } elseif (isset($_POST['deleteImage']) && $_POST['deleteImage'] == 1) {
            // 画像を「削除する」がチェックされた場合
            $profImage = null;
            var_dump(2);
        } else {
            // どちらの操作も行われなかった場合
            $profImage = $user['image'];
            var_dump(3);
        }
    } else {
        die("リクエストが無効です");
    }

    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=php-test','root','root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo $e->getMessage;
        exit();
    }
    
    // データ更新
    $sql = "UPDATE employee SET image = :image, intro_text = :intro_text 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':image', $profImage);
    $stmt->bindValue(':intro_text', $introText);
    $stmt->bindValue(':id', $profId);

    if ($stmt->execute()) {
        header("Location:/php_lesson/pages/profile_view.php?id=&success=4");
        exit;
    } else {
        echo '登録に失敗しました';
    }
} else {
    die("リクエストが無効です");
}

?>