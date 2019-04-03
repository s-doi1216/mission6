<?php 
require(dirname(__FILE__).'/../php/loginFunc.php');
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>ログインページ</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="stylesheet" href="../css/temp_reg.css">
        <link rel="stylesheet" href="../css/login.css">
    </head>
    <body>
        <div class="main">
            <p class="err_mes"><?=$err_mes?></p>
            <p class="sign_in">ログイン</p>
            <form action='login.php' method='post'>
                <input type='text' name='name' placeholder='名前' class="input">
                <input type='password' name='password' placeholder='パスワード' class="input">
                <input type='password' name='password2' placeholder='再度パスワードを入力' class="input">
                <input type='submit' value='ログイン' id='submit' class="submit">
                
        </form>
            <div id="to_loginpage">
                <a href="temp_reg.html" id="link">アカウントがない方はこちら</a>
            </div>
        </div>
    </body>
</html>