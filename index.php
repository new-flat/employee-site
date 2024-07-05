<!-- 初期画面 -->

<?php

require_once("controll.php");
require_once("error_message.php");
require_once("header.html");

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
        <section class="">
            <form  class="search-container" action="search.php" method="get">
                <div class="search-box">
                    <input type="text" name="name" placeholder="氏名を検索" value="<?php echo htmlspecialchars($name_value, ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" value="検索" name="search">🔍</button>
                </div>
                <div class="search-buttons">
                    <div class="search-option">
                        <p>性別で探す</p>
                        <select name="gender">
                            <option disabled selected >性別を選択してください</option>
                            <option value="">全て</option>
                            <option value="1" <?php echo ($gender === 1) ? 'selected' : ''; ?>>男</option>
                            <option value="2" <?php echo ($gender === 2) ? 'selected' : ''; ?>>女</option>
                            <option value="null" <?php echo ($gender === null) ? 'selected' : ''; ?>>不明</option>
                        </select>
                    </div>
                    <div class="search-option">
                        <p>部署で探す</p>
                        <select name="department" id="">
                            <option value="" disabled selected>部署を選択してください</option>
                            <option value="">全て</option>
                            <option value="">A</option>
                            <option value="">B</option>
                            <option value="">C</option> 
                        </select>
                    </div>
                </div>                    
            </form>
        </section>

        <section>
            <div class=list>
            <?php  if(empty($data_array)): ?>
                    <p class = "error_search"><?php echo htmlspecialchars($error_message3, ENT_QUOTES, 'UTF-8'); ?></p>
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

                    <?php foreach($index_stmt as $data): ?>
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
                                if ($data["birth_date"] !== null) {
                                    $birthDate = str_replace("-", "", $data["birth_date"]);
                                // 生年月日から年齢を概算
                                    $age = floor((date('Ymd') - $birthDate) / 10000);
                                    echo htmlspecialchars($age, ENT_QUOTES, 'UTF-8');
                                } else {
                                    echo "不明";
                                }              
                                ?>
                            </td>
                            <td data-label="生年月日"><?php echo htmlspecialchars($data['birth_date'] ?? '不明', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td data-label=""><a class="edit-btn" href="edit.php?id=<?php echo htmlspecialchars($data['id'], ENT_QUOTES, 'UTF-8') ?>">編集</a></td>

                        </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>  
            </div>
        </section>

        <section>
            <div class="pageNation">
            <?php if($total_results > 4): ?>       
            <!-- ◯件中◯-◯件目を表示 -->
                <p><?php echo htmlspecialchars($total_results, ENT_QUOTES, 'UTF-8'); ?>件中<?php echo htmlspecialchars($from_record, ENT_QUOTES, 'UTF-8') ?>-<?php echo htmlspecialchars($to_record, ENT_QUOTES, 'UTF-8');?>件目を表示</p>

                <!-- 前のページボタン -->
                <?php if($page > 1): ?>
                    <a  class="back_page" href="?<?php echo htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $page - 1])), ENT_QUOTES, 'UTF-8'); ?>"><<</a>
                <?php else: ?>    
                    <span class="disabled"><<</span>
                <?php endif; ?>
                
                <!-- ページ番号リンク -->
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if($i >= $page - $range && $i <= $page + $range): ?>
                        <?php if($i == $page): ?>
                            <span class="current_page"><?php  echo htmlspecialchars($i, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php else: ?>    
                            <a class="page_link" href="?<?php echo htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $i])), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($i, ENT_QUOTES, 'UTF-8'); ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endfor; ?> 
                
                <!-- 次のページボタン -->
                <?php if($page < $total_pages): ?>
                    <a  class="next_page" href="?<?php echo htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $page + 1])), ENT_QUOTES, 'UTF-8'); ?>">>></a>  
                <?php else: ?>      
                    <span class="disabled">>></span>
                <?php endif; ?> 
            <?php endif; ?>       
            </div>
        </section>  
    </div>
</body>
</html>
