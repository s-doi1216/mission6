<?php 
require(dirname(__FILE__).'/../php/temp_regFunc.php');
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>仮登録ページ</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="stylesheet" href="../css/temp_reg.css">
    </head>
    <body>
        <?php if (!empty($err_mes)) : ?>
        <div class="main">
            <p class="err_mes"><?=$err_mes?></p>
            <p class="sign_in">新規登録</p>
            <form action="temp_reg.php" method="post">
                <input type="text" name="name" placeholder="名前" class="input">
                <input type="text" name="mailadr" placeholder="メールアドレス" class="input">
                <input type="password" name="password" placeholder="パスワード" class="input">
                <input type="password" name="password2" placeholder="再度パスワードを入力" class="input">
                <input type="submit" value="登録" id="submit" class="submit">
            </form>
            <div id="to_loginpage">
                <a href="../login/login.html" id="link">アカウントをお持ちの方はこちら</a>
            </div>
        </div>
        <?php elseif (!empty($mail_mes)) : ?>
        <div class="main">
            <p class="err_mes"><?=$mail_mes?></p>
            <p class="sign_in">新規登録</p>
            <form action="temp_reg.php" method="post">
                <input type="text" name="name" placeholder="名前" class="input">
                <input type="text" name="mailadr" placeholder="メールアドレス" class="input">
                <input type="password" name="password" placeholder="パスワード" class="input">
                <input type="password" name="password2" placeholder="再度パスワードを入力" class="input">
                <input type="submit" value="登録" id="submit" class="submit">
            </form>
            <div id="to_loginpage">
                <a href="login.html" id="link">アカウントをお持ちの方はこちら</a>
            </div>
        </div>
        <?php endif; ?>
        
    </body>
</html>