<?php

require_once 'employee_controll.php';

if (isset($_GET["id"])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root",
         [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

         //社員情報を取得
        $sql = "SELECT * FROM `employee` WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // 1件のデータを取得

        // 支店情報を取得
        $sql = "SELECT b.branch_name FROM branch b
        JOIN employee e ON b.id = e.branch
        WHERE b.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $branchNames = $stmt->fetchColumn(); //branchテーブルから支店名を取得

        // 資格情報を取得
        $sql = "SELECT q.quali_name FROM emp_quali eq
        JOIN qualification q ON eq.quali_id = q.id
        WHERE eq.employee_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $qualifications = $stmt->fetchAll(PDO::FETCH_COLUMN); // 配列に資格名を格納

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    $errors['id'] = "URLが間違っています";
}

// 生年月日の表示形式
$date_user = new DateTime($user['birth_date']);

// 性別に応じて枠線の色を設定
$borderColor = '';
if ($user['gender'] === 1) {
    $borderColor = 'border: 3px solid blue;';
} elseif ($user['gender'] === 2) {
    $borderColor = 'border: 3px solid red;';
}


?>