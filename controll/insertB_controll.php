<?php

require_once 'branch_function.php';
require_once 'branch_controll.php';
require_once 'error_message.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成

$errors = array();

// CSRF対策用トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $errors['insertBranch'] = $error_message9;
    }
    if (empty($insertTel)) {
        $errors['insertTel'] = $error_message10;
    }
    if (empty($insertPrefecture)) {
        $errors['insertPrefecture'] = $error_message11;
    }

    // 入力内容をデータベースに登録
    if (empty($errors)) {
        // DB接続
        try {
            $pdo = new PDO(
                'mysql:host=localhost;dbname=php-test',
                'root',
                'root',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // トランザクション内のすべての操作が成功した場合のみデータベースに反映。一つでも失敗した場合はすべての操作を取り消し
            $pdo->beginTransaction();

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
            // 'commit'メソッドを使用して、トランザクション内のすべての操作を確定
            $pdo->commit();

            // 同じページ（insert_branch.php）に戻り、URLを追加してメッセージを表示
            header('Location:/php_lesson/pages/insert_branch.php?success=1');
            // リダイレクト後に不要なコードが実行されないようにする
            exit;
        } catch (PDOException $e) {
            // トランザクション内でエラーが発生した場合、操作を取り消し
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // エラーメッセージをログに記録
            error_log($e->getMessage());
        }

        $pdo = null;

    } else {
        // エラーがある場合はフォームに戻る
        header('Location: /php_lesson/pages/insert_branch.php');
        exit();
    }
}
