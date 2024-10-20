<?php
require_once 'header.php';
require_once __DIR__ . '/../controll/branch_controll.php';
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/not_login.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支店一覧</title>
    <link rel="stylesheet" href='/employee_site/css/style.css'>
</head>
<body>
    <div id="main" class="wrapper">
        <section>
            <form class="search-container" action="search_branch.php" method="get">
                <div class="search-branch">
                    <p>支店名を検索</p>
                    <div class="search-box">
                        <input type="text" name="branch_name" value="">
                        <button type="submit">🔍</button>
                    </div>
                </div>
            </form>
        </section>

        <section>
            <div class="list">
                <?php if (empty($branchList)) : ?>
                    <p>該当する支店がありません</p>
                <?php else : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table-title">支店名</th>
                                <th class="table-title">電話番号</th>
                                <th class="table-title">住所</th>
                                <th class="table-title"></th>
                            </tr>
                        </thead>
                        <!-- 検索結果一覧テーブル -->
                        <?php require_once __DIR__ . '/../pages/branch_process.php'; ?>
                    </table>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <div class="pageNation">
                <?php require_once __DIR__ . '/../common/page_branch.php'; ?>
            </div>
        </section>
    </div>
</body>
</html>
