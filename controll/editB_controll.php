<?php
require_once 'branch_controll.php';
require_once 'error_message.php';
require_once 'header.php'; // セッション開始とCSRFトークン生成

// トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        die('リクエストが無効です');
    }
    // トークンが一致するか確認　
    // ⏬なぜhash_equalsを使うのか？
    // 通常の比較演算子(`==`や`===`)を使うと、文字列の長さや部分的な一致によって処理時間が変わることがあり、
    // これを攻撃者が悪用して文字列を推測するリスクがあります。hash_equalsは文字列の全体を一度に比較し、
    // 常に一定の時間をかけるため、タイミング攻撃を防止するのに適しています。
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // トークンが一致する場合
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
            $errors['editBranch'] = $error_message9;
        }
        if (empty($editTel)) {
            $errors['editTel'] = $error_message10;
        }
        if (empty($editPrefecture)) {
            $errors['editPrefecture'] = $error_message11;
        }
        if (empty($newId)) {
            $errors['editId'] = $error_message12; 
        }

        // 編集データをDBに登録
        if (empty($errors)) {
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);

                // 並び順が変更される場合、$id_update_sqlで先にIDを変更する
                if ($originalId !== $newId) {
                    $id_update_sql = "UPDATE `branch` SET id = :new_id WHERE id = :original_id";
                    $id_update_stmt = $pdo->prepare($id_update_sql);
                    $id_update_stmt->bindValue(':new_id', $newId, PDO::PARAM_INT);
                    $id_update_stmt->bindValue(':original_id', $originalId, PDO::PARAM_INT);
                    $id_update_stmt->execute();
                }

                // データの更新
                $update_sql = "UPDATE `branch` SET branch_name = :branch_name, tel = :tel, prefecture = :prefecture, city = :city, address = :address, building = :building WHERE id = :id";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->bindValue(':branch_name', $editBranch, PDO::PARAM_STR);
                $update_stmt->bindValue(':tel', $editTel, PDO::PARAM_STR);
                $update_stmt->bindValue(':prefecture', $editPrefecture, PDO::PARAM_INT);
                $update_stmt->bindValue(':city', $editCity, PDO::PARAM_STR);
                $update_stmt->bindValue(':address', $editAddress, PDO::PARAM_STR);
                $update_stmt->bindValue(':building', $editBuilding, PDO::PARAM_STR);
                $update_stmt->bindValue(':id', $newId, PDO::PARAM_INT); // 新しいIDで更新
                $update_stmt->execute();
                // 成功したら同じページ（edit_branch.php）に戻り、URLを追加してメッセージを表示
                header("Location: edit_branch.php?id=" . $newId . "&success=2");
                // リダイレクト後に不要なコードが実行されないようにする
                exit;

            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            $pdo = null;

        } else {
            // エラーがある場合はフォームに戻る
            $error_data = [
                'editBranch' => $editBranch,
                'editTel' => $editTel,
                'editPrefecture' => $editPrefecture,
                'editCity' => $editCity,
                'editAddress' => $editAddress,
                'editBuild' => $editBuilding,
                'editId' => $newId
            ];

            $error_query = http_build_query(['errors' => json_encode(['messages' => $errors, 'data' => $error_data])]);
            header("Location: edit_branch.php?id=" . $originalId . "&" . $error_query);
            exit;
        }
    } else {
        die("リクエストが無効です");
    }
}
?>
