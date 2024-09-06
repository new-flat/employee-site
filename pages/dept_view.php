<?php

require_once 'header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../controll/pdo_connect.php';

$pdo = getPdoConnection();
$deptList = $pdo->query("SELECT * FROM dept ORDER BY id ASC")->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール編集</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>
<body>
    <div id="main" class="wrapper">
    <table class="quali_table">
        <thead>
            <tr>
                <th class="table-title">部門コード</th>
                <th class="table-title">部門名</th>
                <th class="table-title">親部門</th>
                <th class="table-title"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($deptList as $dept) : ?>
                <tr>
                    <td data-label="部門コード"><?php echo eh($dept->dept_code); ?></td>
                    <td data-label="部門名"><?php echo eh($dept->dept_name); ?></td>
                    <td data-label="親部門"><?php echo eh($dept->parent); ?></td>
                    <td data-label=""><a href="edit_dept.php?id=<?php echo eh($dept->id); ?>" class="edit-btn">編集</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>    
    </table> 
    </div>
