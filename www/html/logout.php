<?php
//定数ファイルの読み込み
require_once '../conf/const.php';
///var/www/html/../model/functions.phpというドキュメントルートを通り汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

//セッションの開始、作成
session_start();
// セッションの変数のクリア
$_SESSION = array();
//セッションクッキーのパラメータを得て、変数で出力
$params = session_get_cookie_params();
//セッションクッキーを削除する
setcookie(session_name(), '', time() - 42000,
  $params["path"],
  $params["domain"],
  $params["secure"],
  $params["httponly"]
);
//セッションを破棄
session_destroy();

//login.phpにリダイレクトする
redirect_to(LOGIN_URL);
