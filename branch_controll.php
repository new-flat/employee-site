<?php

// XSS対策
function eh($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
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

// パラメータの取得
$branchName = isset($_GET["branch_name"]) ? $_GET["branch_name"] : '';
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

//1ページあたりのアイテム数 
$limit = 5;

// データ取得のためのオフセットを計算
$offset = ($page - 1) * $limit;

// SQLクエリの作成
$sql = "SELECT * FROM `branch` WHERE 1=1" ;

// カウントクエリの作成
$count_sql = "SELECT COUNT(*) FROM `branch` WHERE 1=1";
$params = [];


// 検索出力条件
if (!empty($branchName)) {
    $sql .= " AND `branch_name` LIKE :branch_name" ;
    $count_sql .= " AND `branch_name` LIKE :branch_name";
    $params[':branch_name'] = '%' . $branchName . '%';
} 

// クエリ実行
try {
    // PDOを使用してカウントクエリ実行、該当レコードの総数を取得
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_results = $stmt->fetchColumn();
    // 全ページ数の計算
    $total_pages = max(ceil($total_results / $limit), 1);//最低1ページ

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

// 支店編集
$errors = array();
$user = null;

if (isset($_GET["id"])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root");
        $edit_sql = "SELECT * FROM `branch` WHERE id = :id";
        $edit_stmt = $pdo->prepare($edit_sql);
        $edit_stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $edit_stmt->execute();
        $user = $edit_stmt->fetch(PDO::FETCH_OBJ); // 1件のデータを取得

        if (!$user) {
            $errors['id'] = $error_message5;
            exit;
        } 
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
} else {
    $errors['id'] = $error_message5;
}


?>
