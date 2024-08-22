<?php

require_once __DIR__ . '/../controll/branch_function.php';

// DB接続
try {
    $pdo = new PDO('mysql:host=localhost;dbname=php-test', 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    echo $e->getMessage();
}

// 男女別、未登録の人数、その合計数を取得
$gender_count_sql = "SELECT
                        COUNT(CASE WHEN gender = 1 THEN 1 END) AS man,
                        COUNT(CASE WHEN gender = 2 THEN 1 END) AS woman,
                        COUNT(CASE WHEN gender IS NULL THEN 1 END) AS unknown,
                        COUNT(*) AS total_gender
                    FROM `employee`";
$countE_result = $pdo->query($gender_count_sql);
$countE_row = $countE_result->fetch(PDO::FETCH_ASSOC);

$man = $countE_row['man'];
$woman = $countE_row['woman'];
$unknown = $countE_row['unknown'];
$total_gender = $countE_row['total_gender'];

// 部門別の人数を取得
$branch_count_sql = "SELECT branch, COUNT(*) AS employee_each_branch FROM `employee` GROUP BY branch";
$countB_result = $pdo->query($branch_count_sql);

// 結果を格納
$branch_counts = array_fill_keys(array_keys($branches), 0);

// 結果表示
while ($countB_row = $countB_result->fetch(PDO::FETCH_ASSOC)) {
    $branchId = $countB_row['branch'];
    $branch_counts[$branchId] = $countB_row['employee_each_branch'];
}

?>