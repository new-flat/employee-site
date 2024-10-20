<?php
require_once 'pdo_connect.php';
require_once __DIR__ . '/../controll/branch_function.php';

try {
    // DB接続
    $pdo = getPdoConnection();

    // 男女別、未登録の人数、その合計数を取得
    $genderCountSql = "SELECT
                            COUNT(CASE WHEN gender = 1 THEN 1 END) AS man,
                            COUNT(CASE WHEN gender = 2 THEN 1 END) AS woman,
                            COUNT(CASE WHEN gender IS NULL THEN 1 END) AS unknown,
                            COUNT(*) AS total_gender
                        FROM `employee`
                        WHERE is_deleted = 0";
    $countE_result = $pdo->query($genderCountSql);
    $countE_row = $countE_result->fetch(PDO::FETCH_ASSOC);

    $man = $countE_row['man'];
    $woman = $countE_row['woman'];
    $unknown = $countE_row['unknown'];
    $totalGender = $countE_row['total_gender'];

    // 部門別の人数を取得
    $branchCountSql = "SELECT branch, COUNT(*) AS employee_each_branch 
                       FROM `employee` GROUP BY branch
                       WHERE is_deleted = 0";
    $countB_result = $pdo->query($branchCountSql);

    // 結果を格納
    $branchCounts = array_fill_keys(array_keys($branches), 0);

    // 結果表示
    while ($countB_row = $countB_result->fetch(PDO::FETCH_ASSOC)) {
        $branchId = $countB_row['branch'];
        $branchCounts[$branchId] = $countB_row['employee_each_branch'];
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

?>