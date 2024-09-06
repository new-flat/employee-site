<?php
require_once 'header.php';
require_once __DIR__ . '/../controll/total_count.php';
require_once __DIR__ . '/../controll/branch_function.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員管理システム</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>
<body>
    <div id="main" class="wrapper" style="padding-bottom: 50px;">
        <div id="menu-title" class="wrapper">
            <h1 class="title-name">社員集計</h1>
        </div>
        <h2 class="count_title">表1 :男女別社員数</h2>
        <table class="count_table">
            <thead>
                <tr>
                    <th class="table-title">性別</th>
                    <th class="table-title">社員数</th>
                </tr>
                <tr>
                    <td>男性</td>
                    <td><?php echo $man; ?></td>
                </tr>
                <tr>
                    <td>女性</td>
                    <td><?php echo $woman; ?></td>
                </tr>
                <tr>
                    <td>未登録</td>
                    <td><?php echo $unknown; ?></td>
                </tr>
            </thead>
        </table>

        <h2 class="count_title">表2 :部門別社員数</h2>
        <table class="count_table">
            <thead>
                <tr>
                    <th class="table-title">部門</th>
                    <th class="table-title">社員数</th>
                </tr>
            <?php foreach ($branches as $brancheId => $branch_name) : ?>
                <?php $employee_each_branch = $branch_counts[$brancheId] ?>
                <tr>
                    <td><?php echo $branch_name; ?></td>
                    <td><?php echo "$employee_each_branch\n"; ?></td>
                </tr>
            <?php endforeach; ?>    
            </thead>
        </table>
    </div>
</body>
</html>