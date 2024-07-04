<?php
require_once("controll.php");
require_once("error_message.php");

$errors = array();
$id = $_POST['id'] ?? null;
$editName = $_POST['editName'];
$editKana = $_POST['editKana'];
$editGender = $_POST['editGender'];
$editDate = $_POST['editDate'] ?? null;
$editEmail = $_POST['editEmail'];
$editCommuete = isset($_POST['editCommute']) ? trim($_POST['editCommute']) : null ;
$editBlood = $_POST['editBlood'];
$editMarried = $_POST['editMarried'] ?? null;

if ($editDate === '') {
    $editDate = null;
}

// 必須項目のチェック
if (empty($editName)) {
    $errors['editName'] = $error_message1;
}
if (empty($editKana)) {
    $errors['editKana'] = $error_message2;
}
if (empty($editEmail)) {
    $errors['editEmail'] = $error_message6;
}
if (empty($editBlood)) {
    $errors['editBlood'] = $error_message7;
}

$birthDate = !empty($data['birth_date']) ? $data['birth_date'] : null;

// 通勤時間のバリデーション（1以上の整数かどうか）
if (!empty($editCommute)) {
    if (!filter_var($editCommute, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        $errors['editCommute'] = "1以上の整数を入力してください";
    }
}



// 編集データをDBに登録
if (empty($errors)) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=php-test', "root", "root");
        $update_sql = "UPDATE `php-test` SET username = :username, kana = :kana, gender = :gender, birth_date = :birth_date, email = :email, commute_time = :commute_time, blood_type = :blood_type, married = :married WHERE id = :id";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindValue(':username', $editName);
        $update_stmt->bindValue(':kana', $editKana);
        $update_stmt->bindValue(':gender', $editGender === '' ? null : $editGender, PDO::PARAM_INT);
        $update_stmt->bindValue(':birth_date', $editDate);
        $update_stmt->bindValue(':email', $editEmail);
        $update_stmt->bindValue(':commute_time', $editCommuete === '' ? null : $editCommuete, PDO::PARAM_INT);
        $update_stmt->bindValue(':blood_type', $editBlood, PDO::PARAM_STR);
        $update_stmt->bindValue(':married', $editMarried === '' ? null : $editMarried, PDO::PARAM_INT);
        $update_stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            // 成功したら同じページ（edit.php）に戻り、URLを追加してメッセージを表示
            header("Location: edit.php?id=" . $id . "&success=2");
            // リダイレクト後に不要なコードが実行されないようにする
            exit;
        } else {
            echo "登録に失敗しました";
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    $pdo = null;
} else {
    // エラーがある場合はフォームに戻る
    $error_data = [
        'editName' => $editName,
        'editKana' => $editKana,
        'editGender' => $editGender,
        'editDate' => $editDate,
        'editEmail' => $editEmail,
        'editCommute' => $editCommute,
        'editBlood' => $editBlood,
        'editMarried' => $editMarried
    ];

    $error_query = http_build_query(['errors' => json_encode(['messages' => $errors, 'data' => $error_data])]);
    header("Location: edit.php?id=" . $id . "&" . $error_query);
    exit;
}
