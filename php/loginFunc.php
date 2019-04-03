<?php 
require(dirname(__FILE__).'/server_info.php');

$err_mes;

//入力に誤りがないか判定
if (empty($_POST["name"])) {  // emptyは値が空のとき
        $err_mes = '名前が未入力です。';
    }elseif(empty($_POST["password"])){
        $err_mes = 'パスワードが未入力です。';
    }elseif(empty($_POST["password2"])){
        $err_mes = 'パスワードが未入力です。';
    }elseif($_POST["password2"] != $_POST["password"]){
        $err_mes = 'パスワードが確認用と一致しません。';
    }

if(!empty($_POST["name"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password2"] == $_POST["password"]){
    $name = $_POST["name"];
    $password = hash("sha256", $_POST["password"]);
try{
$pdo = new PDO(
    $dsn,$user,$pass,
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION//エラー投げる
    )
);

/*
本登録テーブルを全部取る
foreachで名前とパスがあってるかif文回す
あってたらログイン
*/
$login_query = "SELECT * FROM reg_table";
$login_stmt = $pdo->query($login_query);
$login_result = $login_stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($login_result as $key1 => $val1){
    if($val1[name] == $name && $val1[password] == $password){
        // ログイン
        session_regenerate_id(true);
        $_SESSION['name'] = $val1[name];
        $_SESSION['id'] = $val1[id];
        header("Location: ../main/main.php");  // メイン画面へ遷移
        exit();
    }else{
        $err_mes = "名前かパスワードが違います";
    }
}
}catch (PDOException $e) {
echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}
}else{
$err_mes = "入力に誤りがあります";
}
?>