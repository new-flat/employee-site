<?php

require_once 'employee_controll.php';
require_once 'pdo_connect.php';

if (!isset($_GET["id"])) {
    $errors['id'] = "URLが間違っています";
}

try {
    // DB接続
    $pdo = getPdoConnection();

    $pdo->beginTransaction();

    //社員情報を取得
    $employeeSql = "SELECT * FROM `employee` WHERE id = :id";
    $employeeStmt = $pdo->prepare($employeeSql);
    $employeeStmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $employeeStmt->execute();
    $user = $employeeStmt->fetch(PDO::FETCH_ASSOC); // 1件のデータを取得

    // 支店情報を取得
    $branchSql = "SELECT b.branch_name FROM branch b
    JOIN employee e ON b.id = e.branch
    WHERE e.id = :id"; // employee.idに基づいて支店を取得する
    $branchStmt = $pdo->prepare($branchSql);
    $branchStmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $branchStmt->execute();
    $branchName = $branchStmt->fetchColumn(); //branchテーブルから支店名を取得

    // 資格情報を取得
    $qualiSql = "SELECT q.quali_name FROM emp_quali eq
    JOIN qualification q ON eq.quali_id = q.id
    WHERE eq.employee_id = :id";
    $qualiStmt = $pdo->prepare($qualiSql);
    $qualiStmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $qualiStmt->execute();
    $qualifications = $qualiStmt->fetchAll(PDO::FETCH_COLUMN); // 配列に資格名を格納

    $pdo->commit();
} catch (PDOException $e) {
    echo $e->getMessage();
}

// 生年月日の表示形式
$dateUser = new DateTime($user['birth_date']);

// 性別に応じて枠線の色を設定(男性→青、女性→赤、性別不明→枠線なし)
$borderColor = '';
if ($user['gender'] === 1) {
    $borderColor = 'border: 3px solid blue;';
} elseif ($user['gender'] === 2) {
    $borderColor = 'border: 3px solid red;';
}

?>