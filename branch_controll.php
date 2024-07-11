<?php

$data_array = array();
$branch_stmt = []; // 例: 空の配列で初期化

// XSS対策
function eh($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// ページ番号の取得
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// DB接続
try {
    $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch(PDOException $e) {
    echo $e->getMessage();
    exit;
}

// SQLクエリの作成
$sql = "SELECT `id`, `branch_name`, `tel`, `address` FROM `branch`" ;

// カウントクエリの作成
$count_sql = "SELECT COUNT(*) FROM `branch`";
$params = array();

// 検索条件の処理
if (isset($_GET['branch_name']) && $_GET['branch_name'] !== '') {
    $branch_name = $_GET['branch_name'];
    $sql .= " AND `branch_name` LIKE :branch_name";
    $count_sql .= " AND `branch_name` LIKE :branch_name";
    $params[':branch_name'] = '%' . $branch_name . '%';
}


//1ページあたりのアイテム数 
$limit = 5;
// データ取得のためのオフセットを計算
$offset = ($page - 1) * $limit;

try {
    // PDOを使用してカウントクエリ実行、該当レコードの総数を取得
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_results = $stmt->fetchColumn();
    // 全ページ数の計算
    $total_pages = ceil($total_results / $limit);

    // データクエリ取得の実行
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
    echo $e->getMessage();
    exit;
}

// ページネーション：◯件目ー◯件目を表示
$from_record = ($page - 1) * $limit + 1;
$to_record = min($page * $limit, $total_results);

// 最大5個までページネーションの数字ボタンの範囲を表示
$range = 2;
if ($page == 1 || $page == $total_pages) {
    $range = 4;
} elseif ($page == 2 || $page == $total_pages - 1) {
    $range = 3;
}

// DBの接続を閉じる
$pdo = null;

?>
