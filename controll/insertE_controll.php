<?php
require_once 'pdo_connect.php';
require_once 'error_message.php';
require_once __DIR__ . '/../pages/header.php'; // セッション開始とCSRFトークン生成


$errors = array();

// CSRF対策用トークンが送信されているか確認
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("リクエストが無効です");
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
    die('リクエストが無効です');
}

// トークンが一致しない場合
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('リクエストが無効です');
}

// フォームの入力値を取得
$_SESSION['form_data'] = $_POST;
$insertName = $_POST['insertName'];
$insertKana = $_POST['insertKana'] ;
$insertBranch = $_POST['insertBranch'];
$insertGender = $_POST['insertGender'];
$insertDate = $_POST['insertDate'] ?? null;
$insertEmail = $_POST['insertEmail'];
$insertCommute = $_POST['insertCommute'] ?? null;
$insertBlood = $_POST['insertBlood'] ?? '';
$insertMarried = $_POST['insertMarried'] ?? null;
$insertPass = $_POST['insertPass'];
$insertQuali = $_POST['insertQuali'] ?? null;

// 入力項目のチェック
if (empty($insertName)) {
    $errors['insertName'] = '氏名は必須です';
}

if (empty($insertKana)) {
    $errors['insertKana'] = 'かなは必須です';
}

if (empty($insertGender)) {
    $errors['gender'] = '性別を選択してください';
}

if (empty($insertEmail)) {
    $errors['insertEmail'] = 'メールアドレスは必須です';
} elseif (!filter_var($insertEmail, FILTER_VALIDATE_EMAIL)) {
    $errors['insertEmail'] = '正しい形式のメールアドレスを入力してください';
}

if (empty($insertPass)) {
    $errors['insertPass'] = 'パスワードは必須です';
} elseif (strlen($insertPass) < 8 || (!preg_match('/[a-zA-Z]/', $insertPass) && !preg_match('/[0-9]/', $insertPass))) {
    $errors['insertPass'] = 'パスワードは8文字以上の半角英数字で入力してください';
} else {
    $hashedPass = password_hash($insertPass, PASSWORD_DEFAULT);
}

// 通勤時間のバリデーション（1以上の整数かどうか）
if (!empty($insertCommute) || !filter_var($insertCommute, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
    $errors['insertCommute'] = '1以上の整数を入力してください';
}

// エラーがある場合はフォームに戻る
if (isset($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location:/employee_site/pages/insert_employee.php');
    exit();
}

try {
    // DB接続
    $pdo = getPdoConnection();

    $pdo->beginTransaction();

    // メールアドレスの重複チェック
    $emailStmt = $pdo->prepare("SELECT COUNT(*) FROM employee WHERE email = ?");
    $emailStmt->execute([$insertEmail]);

    if ($emailStmt->fetchColumn() > 0) {
        $errors['insertEmail'] = 'このメールアドレスはすでに登録されています';
    }
    // 社員情報の登録
    $regiSql = "INSERT INTO employee(username, kana, branch, gender, birth_date, email, password, commute_time, blood_type, married) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $regiStmt = $pdo->prepare($regiSql);
    $regiSstmt->execute([$insertName, $insertKana, $insertBranch, $insertGender, $insertDate, $insertEmail, $hashedPass, $insertCommute, $insertBlood, $insertMarried]);

    // 登録した社員のIDを取得
    $employeeId = $pdo->lastInsertId();

    // 保有資格の登録
    if (!empty($insertQuali)) {
        foreach ($insertQuali as $qualiId) {
            $qualiStmt = $pdo->prepare("INSERT INTO emp_quali (employee_id, quali_id) VALUES (?, ?)");
            $qualiStmt->execute([$employeeId, $qualiId]);
        }
    }

    $pdo->commit();

    // 成功メッセージと共にリダイレクト
    unset($_SESSION['form_data']);
    header('Location: /employee_site/pages/insert_employee.php?success=1');
    exit();
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

?>
