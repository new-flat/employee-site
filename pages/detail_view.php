<?php  
require_once 'header.php'; // セッション開始とCSRFトークン生成
require_once __DIR__ . '/../controll/detail.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員詳細</title>
</head>
<body>
    <div id="main" class="wrapper">
        <div id="menu-title">
            <h1 class="title-name">社員詳細：<?php echo eh($user['username']); ?>さん</h1>
        </div>
        <div class="detail">
            <div class="detail-box">
                <div class="emp-img">
                    <img src="/employee_site/controll/images/<?php echo !empty($user['image']) ? eh($user['image']) : 'nowprinting.jpg'; ?>"
                     alt="プロフィール画像" style="<?php echo eh($borderColor) ?>">
                </div>
            </div>
            <div class="detail-box">
                <div class="emp-info">
                    <p class="empDetail">氏名</p>
                    <p><?php echo eh($user['username']); ?></p>
                </div>

                <?php if ($user['birth_date']): ?>
                    <div class="emp-info">
                        <p class="empDetail">生年月日</p>
                        <p><?php echo eh($dateUser->format('Y年n月j日')); ?></p>
                    </div>
                <?php endif ?>

                <div class="emp-info">
                    <p class="empDetail">支店</p>
                    <p><?php echo eh($branchName); ?></p>
                </div>

                <?php if ($user['blood_type']): ?>
                    <div class="emp-info">
                        <p class="empDetail">血液型</p>
                        <p><?php echo eh($user['blood_type']); ?>型</p>
                    </div>      
                <?php endif ?>

                <?php if ($qualifications): ?>
                    <div class="emp-info">
                        <p class="empDetail">資格</p>
                        <p>
                            <?php
                                if (!empty($qualifications)) {
                                    echo(implode(', ', $qualifications)); // 資格をカンマ区切りで表示
                                } else {
                                    echo "なし";
                                }
                            ?>
                        </p>
                    </div>
                <?php endif ?>

                <?php if ($user['intro_text']): ?>
                    <div class="emp-info">
                        <p class="empDetail">紹介文</p>
                        <p><?php echo eh($user['intro_text']); ?></p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</body>
</html>