<?php  
require_once 'header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../controll/pdo_connect.php';
require_once __DIR__ . '/../controll/xss.php';
require_once __DIR__ . '/../controll/branch_function.php';
require_once __DIR__ . '/../controll/not_login.php';

$pdo = getPdoConnection();

// 資格マスタから資格リストを取得
$qualificationList = $pdo->query("SELECT * FROM qualification ORDER BY id ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='/employee_site/css/style.css'>
    <title>Document</title>
</head>
<body>
    <div id="main" class="wrapper">
        <div id="menu-title">
            <h1 class="title-name">資格マスタ</h1>
        </div>
        
        <form action="/employee_site/controll/quali_controll.php" method="post" class="edit_class">
            <!-- 成功メッセージ -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 3) : ?>
                <p class="success-message">更新しました</p>
            <?php endif ?>
            <table class="quali_table">
                <thead>
                    <tr>
                        <th class="table-title">ID</th>
                        <th class="table-title">資格名</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($qualificationList as $quali) : ?>
                        <tr>
                            <td><?php echo eh($quali->id); ?></td>
                            <td data-label="資格名">
                                <!-- qualification[]の配列形式でそれぞれのIdに対応する資格名を送信 -->
                                <input type="text" name="qualification[<?php echo eh($quali->id); ?>]" value="<?php echo eh($quali->quali_name); ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td></td>
                        <td data-label="資格名">
                            <input type="text" name="new_quali" placeholder="新しい資格名を入力">
                        </td>
                    </tr>
                </tbody>    
            </table>
            <button type="submit" class="submit-btn">保存</button>
        </form>
    </div>
</body>
</html>