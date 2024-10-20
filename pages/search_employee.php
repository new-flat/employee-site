<!--　検索後の画面  -->
<?php
require_once 'header.php';
require_once __DIR__ . '/../controll/employee_controll.php';
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/not_login.php';

// トークンを生成し、セッションに保存
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href='/employee_site/css/style.css'>
</head>
<body>
    <div id="main" class="wrapper">
        <section>
            <!-- 検索フォーム -->
            <form class="search-container" action="search_employee.php" method="get">
                <div class="search-box">
                    <input type="text" name="name" placeholder="氏名を検索" value="<?php echo eh($name); ?>">
                    <button type="submit" value="検索" name="search">🔍</button>
                </div>
                <div class="search-buttons">
                    <div class="search-option">
                        <p>性別で探す</p>
                        <select name="gender">
                            <option disabled selected>性別を選択してください</option>
                            <option value="" <?php echo ($gender === '') ? 'selected' : ''; ?>>全て</option>
                            <option value="1" <?php echo ($gender === '1') ? 'selected' : ''; ?>>男</option>
                            <option value="2" <?php echo ($gender === '2') ? 'selected' : ''; ?>>女</option>
                            <option value="null" <?php echo ($gender === 'null') ? 'selected' : ''; ?>>不明</option>
                        </select>
                    </div>
                    <div class="search-option">
                        <p>部署で探す</p>
                        <select name="branch">
                            <option disabled selected>部署を選択してください</option>
                            <option value="" <?php echo eh($branches === '') ? 'selected' : ''; ?>>全て</option>
                            <?php foreach ($branches as $branchId => $branchName) : ?>
                                <option value="<?php echo eh($branchId); ?>" <?php echo eh($branch == $branchId) ? 'selected' : ''; ?>>
                                    <?php echo eh($branchName); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </section>

        <section>
            <div class="list">
                <?php if ($totalResults == 0) : ?>
                    <p class="error_search">該当する社員がいません</p>
                <?php else : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table-title">氏名</th>
                                <th class="table-title">かな</th>
                                <th class="table-title">支店</th>
                                <th class="table-title">性別</th>
                                <th class="table-title">年齢</th>
                                <th class="table-title">生年月日</th>
                                <th class="table-title"></th>
                                <th class="table-title"></th>
                            </tr>
                        </thead>
                        <!-- 検索結果一覧テーブル -->
                        <?php require_once __DIR__ . '/../pages/process.php'; ?>
                    </table>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <!-- 検索結果が5件以上の場合パージネーション表示 -->
            <div class="pageNation">
                <?php require_once __DIR__ . '/../common/page_employee.php'; ?>
            </div>
        </section>
    </div>
</body>
</html>
