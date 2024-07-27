<?php

// XSS対策
function eh($str) {
    return htmlspecialchars($str,ENT_QUOTES, 'UTF-8');
}

// DB接続
try {
    $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch(PDOException $e) {
    echo $e->getMessage();
}

// パラメータの取得
$name = $_GET["name"] ?? '';
$gender = $_GET["gender"] ?? 'null';
$page = (int)($_GET["page"] ?? 1);

//1ページあたりのアイテム数 
$limit = 5;

// データ取得のためのオフセットを計算
$offset = ($page - 1) * $limit;

// SQLクエリの作成
$sql = "SELECT * FROM `php-test` WHERE 1" ;
// カウントクエリの作成
$count_sql = "SELECT COUNT(*) FROM `php-test` WHERE 1";
$params = [];

// 検索出力条件
if (!empty($name)) {
    $sql .= " AND (`username` LIKE :name OR `kana` LIKE :kana)";
    $count_sql .= " AND (`username` LIKE :name OR `kana` LIKE :kana)";
    $params[':name'] = $params[':kana'] = '%' . $name . '%';
}

if (isset($_GET['search'])) {
    if($gender === 'null') {
        $sql .= " AND `gender` IS NULL";
        $count_sql .= " AND `gender` IS NULL";
    }elseif($gender !== '') {
        $sql .= " AND `gender` = :gender";
        $count_sql .= " AND `gender` = :gender";
        $params[':gender'] = (int)$gender;
    } 
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
    $data_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "データベースエラーが発生しました。もう一度お試しください。";
    exit;
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
