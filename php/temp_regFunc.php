<?php 
require(dirname(__FILE__).'/server_info.php');

if(!empty($_POST["name"]) && !empty($_POST["mailadr"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"]==$_POST["password2"]){
    $name = $_POST["name"];
    $mailadr = $_POST["mailadr"];
    $password = hash("sha256", $_POST["password"]);
    $reg_key = uniqid();
    
    try{
        $pdo = new PDO(
            $dsn,$user,$pass,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION//エラー投げる
            )
        );
        
        //仮登録用テーブル
        $create_temp_reg_query = '
        CREATE TABLE IF NOT EXISTS temp_reg_table(
        name CHAR(32) NOT NULL,
        password TEXT NOT NULL,
        mailadr CHAR(64) NOT NULL,
        reg_key CHAR(64) NOT NULL,
        reg_time timestamp
        )';
        $create_temp_reg = $pdo -> prepare($create_temp_reg_query);
        $create_temp_reg -> execute();
        
        //テーブル書き込み&メール送信
        $add_sql = $pdo->prepare("INSERT INTO temp_reg_table(name,password,mailadr,reg_key)VALUES(:name,:password,:mailadr,:reg_key)");
        $add_sql->bindParam(':name',$name,PDO::PARAM_STR);
        $add_sql->bindParam(':password',$password,PDO::PARAM_STR);
        $add_sql->bindParam(':mailadr',$mailadr,PDO::PARAM_STR);
        $add_sql->bindParam(':reg_key',$reg_key,PDO::PARAM_STR);
        $add_sql->execute();
        
        //メールのGETにつけるTIMESTAMPを取得
        $select_sql ="SELECT * FROM temp_reg_table where reg_key='".$reg_key."'";
        $select_result = $pdo->query($select_sql);
        $sel_result = $select_result->fetch(PDO::FETCH_NUM);
        $reg_time = $sel_result[4];
        $reg_time = preg_replace("/( |　)/", "%20", $reg_time );//タイムスタンプそのままだと空白がありURLに載せると正しく認識されないので空白を%20で置き換える
        $url = "http://html/comp_reg.php?name=".urlencode($name)."&reg_key=$reg_key&reg_time=$reg_time";//ユーザー名が日本語だとリンクが作れないため$nameのみエンコード
        $subject = "登録確認メール";
        $message = "まだ本登録は完了していません。以下のリンクからアクセスして登録完了となります。\r\n".$url;
        $headers = "From: test@test.com";
        mail($mailadr, $subject, $message);
        if(mail($mailadr, $subject, $message,$headers)){
        $mail_mes =  "メール送信しました。メールを開いて登録完了です";
        }else{
            $mail_mes =  "メールの送信に失敗しました。";
        }
    }catch (PDOException $e) {
        echo $e->getMessage()." - ".$e->getLine().PHP_EOL;

    }
    
}else{
    $err_mes = "入力に不備があります";
}
?>