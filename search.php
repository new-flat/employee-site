    <!--　検索後の画面  -->

<?php  

require_once("header.html");
require_once("controll.php");
require_once("error_message.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div id="main" class="wrapper">
       <div class="form">
            <form class="formu" action="search.php" method="get">
                <div class="form-list">
                    <p>氏名</p>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($name_value, ENT_QUOTES, 'UTF-8'); ?>">
                </div>  
                <div class="form-list">  
                    <p>性別</p>
                    <select name="gender">
                        <option value="" selected>全て</option>
                        <option value="null" <?php echo ($gender === 'null') ? 'selected' : ''; ?>>不明</option>
                        <option value="1" <?php echo ($gender === '1') ? 'selected' : ''; ?>>男</option>
                        <option value="2" <?php echo ($gender === '2') ? 'selected' : ''; ?>>女</option>
                    </select>
                </div>  
              
                <input class="search" type="submit" value="検索" name="search">
                      
            </form>
        </div>
        <div class="list">
          <?php  if($total_results == 0): ?>
            <p class = "error_search"><?php echo $error_message3; ?></p>
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

                <?php foreach($data_array as $data): ?>
                <tbody>
                    <tr>
                        <th><?php echo htmlspecialchars($data["username"], ENT_QUOTES, 'UTF-8'); ?></th>
                        <td data-label="かな"><?php echo htmlspecialchars($data['kana'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td data-label="性別">
                            <?php 
                                if($data["gender"] === 1){
                                    echo "男";
                                } elseif($data["gender"] === 2) {
                                    echo "女";
                                } else {
                                    echo "不明";
                                }
                            ?>
                        </td>
                        <td data-label="年齢">
                            <?php 
                            $birthDate = str_replace("-", "", $data["birth_date"]);
                            $age = floor((date('Ymd') - $birthDate) / 10000);
                            echo $age;
                            ?>
                        </td>
                        <td data-label="生年月日"><?php echo htmlspecialchars($data['birth_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td data-label=""><a class="edit-btn" href="edit.php?id=<?php echo($data['id']) ?>">編集</a></td>
                    </tr>
                </tbody>
                <?php endforeach; ?>
            </table>
          <?php endif; ?>  
        </div>

        


        <div class="pageNation" >
    <!-- 検索結果が5件以上の場合には、ページネーションが表示 -->
            <?php if($total_results > 4): ?>     
            <!-- ◯件中◯-◯件目を表示 -->
                <p class=><?php echo $total_results; ?>件中<?php echo $from_record ?>-<?php echo $to_record ?>件目を表示</p>

                <!-- 前のページボタン -->
                <?php if($page > 1): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET,['page' => $page - 1])); ?>"><<</a>
                <?php else: ?>    
                    <span class="disabled"><<</span>
                <?php endif; ?>
                
                <!-- ページ番号リンク -->
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if($i >= $page - $range && $i <= $page + $range): ?>
                        <?php if($i == $page): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>    
                            <a href="?<?php echo http_build_query(array_merge($_GET,['page' => $i])); ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endfor; ?> 
                
                <!-- 次のページボタン -->
                <?php if($page < $total_pages): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">>></a>  
                <?php else: ?>      
                    <span class="disabled">>></span>
                <?php endif; ?>   
            <?php endif; ?>    
        </div>
    </div>
</body>
</html>