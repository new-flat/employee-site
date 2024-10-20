<?php

require_once __DIR__ . '/../controll/employee_controll.php';

// 選択された社員のデータを取得
$errors = array();
$user = null;

if (isset($_GET["id"])) {
    try {
        // DB接続
        $pdo = getPdoConnection();
        $editSql = "SELECT * FROM `employee` WHERE id = :id";
        $editStmt = $pdo->prepare($editSql);
        $editStmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $editStmt->execute();
        $user = $editStmt->fetch(PDO::FETCH_OBJ); // 1件のデータを取得

        if ($user->email === "") {
            $user->email = null;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    $errors['id'] = "URLが間違っています";
}

// 資格マスタから資格リストを取得
$qualificationList = $pdo->query("SELECT * FROM qualification ORDER BY id ASC")->fetchAll();

// 保有資格クエリ作成
$qualiIdSql = "SELECT quali_id FROM emp_quali WHERE employee_id = :employee_id";
$qualiStmt = $pdo->prepare($qualiIdSql);
$qualiStmt->bindValue(':employee_id', $_GET["id"], PDO::PARAM_INT);
$qualiStmt->execute();

$qualiIds = [];

// 資格IDを配列に格納
$qualiIds = $qualiStmt->fetchAll(PDO::FETCH_COLUMN, 0);

// 血液型定義
$blood_types = ["A" => "A型", "B" => "B型", "O" => "O型", "AB" => "AB型", "" => "不明"];
// 選択された血液型を格納
$selected_blood = $errors['data']['editBlood'] ?? $user->blood_type ?? '';


?>