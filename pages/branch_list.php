<?php
require_once __DIR__ . '/../controll/branch_controll.php';
require_once __DIR__ . '/../controll/branch_function.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支店一覧</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>
<body>
    <div id="main" class="wrapper">
        <header><h1 class="title_branch">支店一覧</h1></header>
        <section>
            <form class="search-container" action="search_branch.php" method="get">
                <div class="search-branch">
                    <p>支店を検索</p>
                    <input type="text" name="branch_name" value="">
                    <button type="submit">🔍</button>
                </div>
            </form>
        </section>

        <section>
            <div class="list">
                <?php if (empty($dataArray)) : ?>
                    <p class="error_search">該当する支店がありません</p>
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
                        <?php require_once __DIR__ . '/../search_result/branch_process.php'; ?>
                    </table>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <div class="pageNation">
                <?php require_once __DIR__ . '/../standardize/page_nation.php'; ?>
            </div>
        </section>
    </div>
</body>
</html>
