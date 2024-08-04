<?php
require_once 'header.php';
require_once __DIR__ . '/../controll/employee_controll.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href='/php_lesson/css/style.css'>
</head>
<body>
    <div id="main" class="wrapper">
        <section>
            <form class="search-container" action="search_employee.php" method="get">
                <input type="hidden" name="csrf_token" value="<?php echo eh($_SESSION['csrf_token']); ?>">
                <div class="search-box">
                    <input type="text" name="username" placeholder="氏名を検索" value="<?php echo eh($name); ?>">
                    <button type="submit" name="search">🔍</button>
                </div>
                <div class="search-buttons">
                    <div class="search-option">
                        <p>性別で探す</p>
                        <select name="gender">
                            <option disabled selected>性別を選択してください</option>
                            <option value="" <?php echo ($gender === '') ? 'selected' : ''; ?>>全て</option>
                            <option value="1" <?php echo ($gender === '1') ? 'selected' : ''; ?>>男</option>
                            <option value="2" <?php echo ($gender === '2') ? 'selected' : ''; ?>>女</option>
                            <option value="null" <?php echo ($gender === null) ? 'selected' : ''; ?>>不明</option>
                        </select>
                    </div>
                    <div class="search-option">
                        <p>部署で探す</p>
                        <select name="department">
                            <option value="" disabled selected>部署を選択してください</option>
                            <option value="">全て</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option> 
                        </select>
                    </div>
                </div>                    
            </form>
        </section>

        <section>
            <div class="list">
                <?php if (empty($employees)): ?>
                    <p class="error_search">該当する社員がいません</p>
                <?php else: ?>    
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table-title">氏名</th>
                                <th class="table-title">かな</th>
                                <th class="table-title">性別</th>
                                <th class="table-title">年齢</th>
                                <th class="table-title">生年月日</th>
                                <th class="table-title"></th>
                            </tr>
                        </thead>
                        <!-- 検索結果一覧テーブル -->
                        <?php require_once __DIR__ . '/../search_result/process.php'; ?>
                    </table>
                <?php endif; ?>  
            </div>
        </section>

        <section>
            <!-- 検索結果が5件以上の場合パージネーション表示 -->
            <div class="pageNation">
            <?php require_once __DIR__ . '/../common/page_nation.php'; ?>
            </div>
        </section>  
    </div>
</body>
</html>
