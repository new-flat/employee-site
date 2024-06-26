<?php

$data_array = array();

// DB接続
try {
    $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root");
} catch(PDOException $e) {
    echo $e->getMessage();
}

// SQLクエリの作成
$sql = "SELECT `id`, `username`, `kana`, `gender`, `birth_date` FROM `php-test` WHERE 1 " ;
// カウントクエリの作成
$count_sql = "SELECT COUNT(*) FROM `php-test` WHERE 1 ";
$params = array();



$name = isset($_GET["name"]) ? $_GET["name"] : '';
$gender = isset($_GET["gender"]) ? $_GET["gender"] : 'null';
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

//1ページあたりのアイテム数 
$limit = 5;
// データ取得のためのオフセットを計算
$offset = ($page - 1) * $limit;






// 全ページ数の計算：総件数20件、1ページあたり5件表示する場合、全ページはceil(20 / 5) = 4

$index_sql = "SELECT * FROM `php-test`"; 

$index_stmt = $pdo->query($index_sql);


$index_sql .= " LIMIT :limit OFFSET :offset";
$index_stmt = $pdo->prepare($index_sql);
foreach ($params as $key => $value) {
    $index_stmt->bindValue($key, $value);
}
    $index_stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $index_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $index_stmt->execute();
    
 
    

// 検索出力条件
if (!empty($name)) {
    $sql .= " AND (`username` LIKE :name OR `kana` LIKE :kana)";
    $count_sql .= " AND (`username` LIKE :name OR `kana` LIKE :kana)";
    $params[':name'] = '%' . $name . '%';
    $params[':kana'] = '%' . $name . '%';
    $name_value = $_GET['name'];
} else {
    $name_value = '';
}


if($gender === 'null') {
    $sql .= " AND `gender` IS NULL";
    $count_sql .= " AND `gender` IS NULL";
}elseif($gender !== '') {
    $sql .= " AND `gender` = :gender";
    $count_sql .= " AND `gender` = :gender";
    $params[':gender'] = (int)$gender;
} 




// クエリ実行
try {
    // PDOを使用してカウントクエリ実行、該当レコードの総数を取得
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_results = $stmt->fetchColumn();
    // 全ページ数の計算：総件数20件、1ページあたり5件表示する場合、全ページはceil(20 / 5) = 4
    $total_pages = ceil($total_results / $limit);

    // データクエリ取得の実行
    // LIMITとOFFSETを使って特定の範囲のデータだけを取得する
    $sql .= " LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data_array = $stmt->fetchAll();
} catch (PDOException $e) {
    echo $e->getMessage();
}


// ページネーション：◯件目ー◯件目を表示
$from_record = ($page - 1) * $limit + 1;
if($page == $total_pages && $total_results % $page !== 0) {
    $to_record = ($page - 1) * $limit + $total_results % $limit;
} else {
    $to_record = $page * $limit;
}

// 最大5個までページネーションの数字ボタンの範囲を表示
if ($page == 1 || $page == $total_pages) {
    $range = 4;
} elseif ($page == 2 || $page == $total_pages - 1) {
    $range = 3;
} else {
    $range = 2;
}






// DBの接続を閉じる
$pdo = null

?>
