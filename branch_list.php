<?php
 require_once("header.php");
 require_once("branch_controll.php");

// 必要な変数が正しく設定されているか確認
$data_array = isset($data_array) ? $data_array : [];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支店一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="main" class="wrapper">
        <section>
            <div id="menu-title" class="wrapper">
                <div class="title-name">支店一覧</div>
            </div>
            <form class="search-container" action="branch_search.php" method="get">
                <div class="search-branch">
                    <p>支店を検索</p>
                    <input type="text" name="branch_name" value="">
                    <button type="submit" value="検索">🔍</button>
                </div>
            </form>
        </section>

        <section>
            <div class="list">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="table-title">支店名</th>
                            <th class="table-title">電話番号</th>
                            <th class="table-title">住所</th>
                            <th class="table-title"></th>
                        </tr>
                    </thead>
                    <?php if (!empty($data_array)) : ?>
                        <?php foreach ($data_array as $data) : ?>
                        <tbody>
                            <tr>
                                <th><?php echo eh($data["branch_name"]); ?></th>
                                <td data-label="電話番号"><?php echo eh($data['tel']); ?></td>
                                <td data-label="住所"><?php echo eh($data['address']); ?></td>
                                <td data-label=""><a class="edit-btn" href="edit_branch.php?id=<?php echo $data['id']; ?>">編集</a></td>
                            </tr>
                        </tbody>
                    
                        <?php endforeach; ?>

                        
                    <?php else : ?>
                        <?php echo "データ無し"; ?>
                    <?php endif; ?>
                </table>
            </div>
        </section>

        <section>
            <div class="pageNation">
            <?php if($total_results > 4): ?>       
            <!-- ◯件中◯-◯件目を表示 -->
                <p><?php echo eh($total_results); ?>件中<?php echo eh($from_record) ?>-<?php echo eh($to_record);?>件目を表示</p>

                <!-- 前のページボタン -->
                <?php if($page > 1): ?>
                    <a  class="back_page" href="?<?php echo eh(http_build_query(array_merge($_GET, ['page' => $page - 1]))); ?>"><<</a>
                <?php else: ?>    
                    <span class="disabled"><<</span>
                <?php endif; ?>
                
                <!-- ページ番号リンク -->
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if($i >= $page - $range && $i <= $page + $range): ?>
                        <?php if($i == $page): ?>
                            <span class="current_page"><?php  echo eh($i); ?></span>
                        <?php else: ?>    
                            <a class="page_link" href="?<?php echo eh(http_build_query(array_merge($_GET, ['page' => $i]))); ?>"><?php echo eh($i); ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endfor; ?> 
                
                <!-- 次のページボタン -->
                <?php if($page < $total_pages): ?>
                    <a  class="next_page" href="?<?php echo eh(http_build_query(array_merge($_GET, ['page' => $page + 1]))); ?>">>></a>  
                <?php else: ?>      
                    <span class="disabled">>></span>
                <?php endif; ?> 
            <?php endif; ?>       
            </div>
        </section>  
    </div>
</body>
</html>