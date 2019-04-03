<?php 
require(dirname(__FILE__).'/server_info.php');

$name = $_GET['name'];
$reg_key = $_GET['reg_key'];
$reg_time = $_GET['reg_time'];
$err_mes;
try{
    $pdo = new PDO(
        $dsn,$user,$pass,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION//エラー投げる
        )
    );
    
    //本登録用テーブル
    $create_reg_query = '
    CREATE TABLE IF NOT EXISTS reg_table(
    id INT NOT NULL AUTO_INCREMENT primary key,
    name CHAR(32) NOT NULL,
    password TEXT NOT NULL,
    mailadr CHAR(64) NOT NULL
    )';
    $create_reg = $pdo -> prepare($create_reg_query);
    $create_reg -> execute();
    
    //ユーザーの仮登録情報取得
    $select_sql ="SELECT * FROM temp_reg_table where reg_key='".$reg_key."'";
    $select_result = $pdo->query($select_sql);
    $sel_result = $select_result->fetch(PDO::FETCH_NUM);

    if($sel_result[3] == $reg_key){//今の時間とreg_timeが１時間以内ならtrue、falseならタイムアウトのechoと行削除。タイムスタンプを比較
        $now_time = time();//現在のタイムスタンプ
        $reg_time = strtotime($reg_time);//仮登録時の時刻をタイムスタンプ化
        
        if($now_time - $reg_time < 3600){//1時間以内なら
            $name = $sel_result[0];
            $password = $sel_result[1];
            $mailadr = $sel_result[2];
            
            //本登録テーブルに書き込み
            $add_sql = $pdo->prepare("INSERT INTO reg_table(name,password,mailadr)VALUES(:name,:password,:mailadr)");
            $add_sql->bindParam(':name',$name,PDO::PARAM_STR);
            $add_sql->bindParam(':password',$password,PDO::PARAM_STR);
            $add_sql->bindParam(':mailadr',$mailadr,PDO::PARAM_STR);
            $add_sql->execute();
            
            //仮登録の行を削除
            $delete_sql = "delete from temp_reg_table where reg_key='".$reg_key."'";
            $delete_result = $pdo->query($delete_sql);
            
            $err_mes = "登録完了！ユーザー名：".$name."<br>"."パスワード：***(セキュリティーのため非表示)";
        }else{//1時間以上なら
            $err_mes = "タイムアウト";
        }
    }else{
        $err_mes = "キーが違います。";
    }
}catch (PDOException $e) {
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;

}
?>