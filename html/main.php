<?php
session_start();

if(empty($_SESSION['id']) && empty($_SESSION['name'])){
    print("
    <!DOCTYPE HTML>
<html>
    <head>
        <meta charset='utf-8'>
        <title>メインページ</title>
    </head>
    <body>
        <div class='wrap'>
            <p>ユーザー情報が取得できませんでした。ログインしてください。</p>
            <h2><a href='../login/login.html'>ログインページ</a></h2>
        </div>
    </body>
</html>
    ");
    exit();
}
require(dirname(__FILE__).'/../php/mainFunc.php');
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>メインページ</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript">
            var json_arr = JSON.parse('<?php echo $json_arr; ?>');//phpから別ファイルのjsへ変数を渡すための宣言
        </script>
        <script type="text/javascript" src="../js/main.js"></script>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="stylesheet" href="../css/main.css">
    </head>
    <body>
        <div class="wrap">
            <div class="userinfo">
                <p><?=$name?>さん</p>
            </div>
            <ul class="tab_menu clearfix">
                <li class="active">個人</li>
                <li>全体</li>
            </ul>
            <div class="area">
                <div class="calender show div">
                <table>
                    <tr>
                        <th colspan="2">
                            <?php if (date("n", $date_timestamp) == date("n")): ?>
                            <span>前月</span>
                            <?php else: ?>
                            <a href="main.php?date=<?php print(strtotime("-1 month",$first_date)); ?>">前月</a>
                            <?php endif; ?>
                        </th>
                        <th colspan="3" class="th"><?php print(date("Y", $date_timestamp) . "年" . date("n", $date_timestamp) . "月"); ?></th>
                        <th colspan="2">
                            <?php if (date("n", $date_timestamp) == date("n")+2): ?>
                            <span>次月</span>
                            <?php else: ?>
                            <a href="main.php?date=<?php print(strtotime("+1 month",$first_date)); ?>">次月</a>
                            <?php endif; ?>
                        </th>
                    </tr>
                    <tr>
                        <th>日</th>
                        <th>月</th>
                        <th>火</th>
                        <th>水</th>
                        <th>木</th>
                        <th>金</th>
                        <th>土</th>
                    </tr>
                    <tr>
                        <?php 
                          for ($i = 0; $i < $week[$first_day]; $i++) { // カレンダーの最初の空白部分 
                           print("<td></td>\n"); 
                          } 
                        
                          for ($day = $first_day; $day <= $last_day; $day++) { //カレンダーの日付部分
                              if ($week[$day] == 0) { //日曜だったら
                              print("</tr>\n<tr>\n"); //テーブル改行
                           } 
                              if($sel_result[$day+1] == 'a'){
                                  print("<td class='a'>$day</td>\n");
                              }elseif($sel_result[$day+1] == 'b'){
                                  print("<td class='b'>$day</td>\n");
                              }elseif($sel_result[$day+1] == 'c'){
                                  print("<td class='c'>$day</td>\n");
                              }else{
                                  print("<td>$day</td>\n"); 
                              }
                          } 
                        
                          for ($i = $week[$last_day] + 1; $i < 7; $i++) { // カレンダーの最後の空白部分 
                           print ("<td></td>\n"); 
                          } 
                        ?>
                    </tr>
                </table>
                    
                    <div class="calender_info">
                        <span class="a">10:00~14:00 -> 赤</span>
                        <span class="b">14:00~18:00 -> 青</span>
                        <span class="c">18:00~22:00 -> 緑</span>
                    </div>
                    <div class="form">
                <p>シフト希望</p>
                <form action='main.php' method='post' id="form">
                    <div id="column_wrap">
                        <div class="column">
                            <span>希望日</span>
                            <select name="day[]">
                                <option value="">-</option>
                                <?php
                                for($day = $first_day; $day <= $last_day; $day++){
                                    print("<option value='$day'>$day</option>");
                                }
                                ?>
                                
                            </select>
                            <span>希望時間</span>
                            <select name="time[]">
                                <option value="">-</option>
                                <option value="a">10:00~14:00</option>
                                <option value="b">14:00~18:00</option>
                                <option value="c">18:00~22:00</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" id="button">希望日を追加</button>
                    <input type='submit' value='送信' id='submit' class="submit" name="submit">
                </form>
            </div>
            </div>
                <div class="calender2 div"><table>
                    <tr>
                        <th colspan="2">
                            <?php if (date("n", $date_timestamp) == date("n")): ?>
                            <span>前月</span>
                            <?php else: ?>
                            <a href="main.php?date=<?php print(strtotime("-1 month",$first_date)); ?>">前月</a>
                            <?php endif; ?>
                        </th>
                        <th colspan="3" class="th"><?php print(date("Y", $date_timestamp) . "年" . date("n", $date_timestamp) . "月"); ?></th>
                        <th colspan="2">
                            <?php if (date("n", $date_timestamp) == date("n")+2): ?>
                            <span>次月</span>
                            <?php else: ?>
                            <a href="main.php?date=<?php print(strtotime("+1 month",$first_date)); ?>">次月</a>
                            <?php endif; ?>
                        </th>
                    </tr>
                    <tr>
                        <th>日</th>
                        <th>月</th>
                        <th>火</th>
                        <th>水</th>
                        <th>木</th>
                        <th>金</th>
                        <th>土</th>
                    </tr>
                    <tr>
                        <?php 
                          // カレンダーの最初の空白部分 
                          for ($i = 0; $i < $week[$first_day]; $i++) { 
                           print("<td></td>\n"); 
                          } 
                        
                        
                          for ($day = $first_day; $day <= $last_day; $day++) { 
                              if ($week[$day] == 0) { //日曜だったら
                              print("</tr>\n<tr>\n"); //テーブル改行
                           } 
                              //$all_arr["$day]があれば、classとaタグつきのtd
                              if(!empty($all_arr["$day"])){
                                  print("<td class='d'><a href='javascript:void(0)' name='$day' class='a_d'>$day</a></td>\n"); 
                              }else{
                                  //print("<td>$day</td>\n"); 
                                  print("<td class='e'><a href='javascript:void(0)' name='$day' class='a_d'>$day</a></td>\n"); 
                              }
                          } //日付部分
                        
                          // カレンダーの最後の空白部分 
                          for ($i = $week[$last_day] + 1; $i < 7; $i++) { 
                           print ("<td></td>\n"); 
                          } 
                        ?>
                    </tr>
                </table>
                    <div class="calender_info">
                        <span class="info_d">希望者あり -> 紫 日付をクリックで詳細</span>
                    </div>
                    <div class="info" id="info">
                        <p id="info_p"></p>
                        <ul class="wrap_ul clearfix">
                            <li class="a list">
                                <span>10:00~14:00</span>
                                <ul class="inner_ul" id="ul_a">
                                    <!--jsでliを追加-->
                                </ul>
                            </li>
                            <li class="b list">
                                <span>14:00~18:00</span>
                                <ul class="inner_ul" id="ul_b">
                                    <!--jsでliを追加-->
                                </ul>
                            </li>
                            <li class="c list">
                                <span>18:00~22:00</span>
                                <ul class="inner_ul" id="ul_c">
                                    <!--jsでliを追加-->
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <h2><a href="logout.php">ログアウト</a></h2>
        </div>
        
    </body>
</html>