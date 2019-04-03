<?php
session_start();

$_SESSION = array();// セッションの変数のクリア
if (isset($_COOKIE[session_name()])) {//ブラウザ側のsessionデータの削除
    setcookie(session_name(), '', time()-42000, '/');
}
session_destroy();// セッションの破壊
?>