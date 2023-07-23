<!-- ！！bindをしていない危険なコード！！ -->

<?php
ini_set('display_errors', 'On'); // エラーを表示させるようにしてください
error_reporting(E_ALL); // 全てのレベルのエラーを表示してください

$view = '';

// POST通信の場合のみ、実行
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //1. POSTデータ取得
    $id = $_POST['id'];

    //2. DB接続します
    include("funcs.php");  //funcs.phpを読み込む（関数群）
    $pdo = db_conn();      //DB接続関数


    //３．データ登録SQL作成
    // !! bindしていない !!
    $sql = "SELECT * FROM gs_an_table WHERE id=:id";
    $stmt = $pdo->prepare($sql); // statement
    $stmt->bindValue(':id', $id, PDO::PARAM_INT); //bindValueはフィルター。フォームにコードを入力される攻撃（SQL INJECTION）からデータを守る
    $status = $stmt->execute();

    $sql = "SELECT * FROM gs_an_table WHERE id=" . $id;
    $stmt = $pdo->prepare($sql); // statement
    $status = $stmt->execute();

    //４．データ登録処理後
    if ($status == false) {
        //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
        $error = $stmt->errorInfo();
        exit("SQL_Error:" . $error[2]);
    } else {
        while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $view .= '<p>';
            $view .= $res['id'] . ', ' . $res['name'];
            $view .= '</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection</title>
</head>

<body>
    <div>
        <form action="injection.php" method="post">
            ID: <input type="text" name="id">
            <input type="submit" value="取得">
        </form>
    </div>
    <div>
        <p>取得結果</p>
        <div>
            <?= $view ?>
        </div>
    </div>
</body>

</html>