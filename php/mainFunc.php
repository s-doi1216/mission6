<?php 
require(dirname(__FILE__).'/server_info.php');

$id = $_SESSION['id'];
$name = $_SESSION["name"];

//カレンダー作成のための変数準備
if(!empty($_GET["date"])){//もし次月、前月が指定されていたら
    $date_timestamp = $_GET["date"];//timestampを指定された月に
}else{//指定がなければ
    $date_timestamp = time();//現在のtimestamp
}
$month = date("m", $date_timestamp); //01~12月
$select_month = date("M", $date_timestamp);//Jan~Dec
$year = date("Y", $date_timestamp); 
$first_date = mktime(0, 0, 0, $month, 1, $year);//0時0分0秒?月1日?年
$last_date = mktime(0, 0, 0, $month + 1, 0, $year);//0時0分0秒?の翌月0日(?月の最終日)?年
// 最初の日と最後の日の｢日にち」の部分だけ数字で取り出す。 
$first_day = date("j", $first_date); 
$last_day = date("j", $last_date);

for($day = $first_day; $day <= $last_day; $day++){//曜日の取得
    $day_timestamp = mktime(0,0,0,$month,$day,$year);//0時0分0秒?月それぞれの日?年(その日のタイムスタンプを取得)
    $week[$day] = date("w",$day_timestamp);//0(日)~6(土)
}
try{
    $pdo = new PDO(
        $dsn,$user,$pass,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION//エラー投げる
        )
    );
    function create_query($mon){//テーブル作成の命令文を返す関数
        $create_query;
        global $first_day;
        global $last_day;
        global $year;
        for($day =  $first_day; $day <=  $last_day; $day++){
        if($day ==  $first_day){
                $create_query .= 
                    "CREATE TABLE IF NOT EXISTS ";
                $create_query .= $year;
                $create_query .= "_";
                $create_query .= $mon;
                $create_query .= "_table(
                    id INT NOT NULL primary key,
                    name CHAR(32) NOT NULL,";
                 $create_query .= $day;
                 $create_query .= "day CHAR(32),";
            }elseif($day >  $first_day && $day <  $last_day){
                 $create_query .= $day;
                 $create_query .= "day CHAR(32),";
            }elseif($day ==  $last_day){
                 $create_query .= $day;
                 $create_query .= "day CHAR(32)";
                 $create_query .= ")";
            }
        }
        return $create_query;
    }
    $query = create_query($select_month);
    $create_table = $pdo -> prepare($query);
    $create_table -> execute();
    
    $sub=$_POST['submit'];
    if ($sub != ""){
    if(count(array_filter($_POST['day'])) != 0 && !empty($_POST["time"])){
        $post_day = $_POST["day"];//$post_day[0]->?日 $post_day[1]->?日...
        $post_time = $_POST["time"];//$post_time[0]->a,b,cのどれか $post_time[1]->a,b,cのどれか...
        
        for($i = 0; $i < count($post_day); $i++){//postされてる分だけ回す
            $insert = "INSERT INTO ".$year."_".$select_month."_table (id,name,".$post_day[$i]."day) VALUES (".$id.',"'.$name.'","'.$post_time[$i].'") ON DUPLICATE KEY UPDATE '.$post_day[$i].'day ="'.$post_time[$i].'"';
            $pdo->query($insert);
        }
    }
}
    //テーブルの中身取得(ログイン中のユーザの情報)
    $re = "SELECT * FROM ".$year."_".$select_month.'_table WHERE id="'.$id.'"';
    $select_result = $pdo->query($re);
    $sel_result = $select_result->fetch(PDO::FETCH_NUM);
    
    //テーブルの中身全部を取得
    $re_all = "SELECT * FROM ".$year."_".$select_month."_table";
    $select_all = $pdo->query($re_all);
    $sel_all = $select_all->fetchAll(PDO::FETCH_NUM);
    
    $all_arr =array();//全体用のカレンダーで参照する情報
    foreach($sel_all as $key1 => $data1){//0=>aaaさんの行,1=>bbbさん...
        foreach($data1 as $key2 => $data2){//0=>id,1=>name,2=>1day...
            if($key2 > 1){//idとnameを除く
                $key2_day = $key2-1;//カレンダーの日付と合わせる
                if(!empty($data2)){//シフトa,b,cが書かれていたら
                $all_arr["$key2_day"]["$data2"][] = $data1[1];//arr[日付のindex][シフトabcのどれか]=[名前]
                }
            }
        }
    }
    $json_arr = json_encode($all_arr);//jsへ渡すためにjson形式に
}catch (PDOException $e) {
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}
?>