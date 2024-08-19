<?php  
require_once 'header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../controll/xss.php';
require_once __DIR__ . '/../controll/quali_controll.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div id="menu-title" class="wrapper">
        <h1 class="title-name">資格マスタ</h1>
    </div>
    <div id="main" class="wrapper">
        <!-- 成功メッセージ -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 3) : ?>
            <p style="margin:0">更新しました</p>
        <?php endif ?>
        
        <form action="/php_lesson/controll/quali_controll.php" method="post" class="edit_class">
            <table class="count_table" style="padding-top: 50px;">
                <thead>
                    <tr>
                        <th class="table-title">ID</th>
                        <th class="table-title">資格名</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($qualificationList as $quali) : ?>
   1                     <tr>
                            <td><?php echo eh($quali['id']); ?></td>
                            <td data-label="資格名">
                                <!-- qualification[]の配列形式でそれぞれのIdに対応する資格名を送信 -->
                                <input type="text" name="qualification[<?php echo eh($quali['id']); ?>]" value="<?php echo eh($quali['quali_name']); ?>">
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
            <button type="submit" class="edit-btn" style="margin: 50px;">保存</button>
        </form>
    </div>
</body>
</html>