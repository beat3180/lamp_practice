<?php
//定数ファイルの読み込み
require_once '../conf/const.php';
///var/www/html/../model/functions.phpというドキュメントルートを通り汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
///var/www/html/../model/user.phpというドキュメントルートを通りuserデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
///var/www/html/../model/item.phpというドキュメントルートを通りitemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';

//セッションの開始、作成
session_start();

//(isset($_SESSION['user_id'])を取得しようとして、取得できなかった場合TRUEを返す
if(is_logined() === false){
  // header関数処理を実行し、login.phpページへリダイレクトする
  redirect_to(LOGIN_URL);
}

//CSRFトークンの生成、セッションに登録する
$token = get_csrf_token();

//DB接続
$db = get_db_connect();

//$_SESSION['user_id']を取得する
$user = get_login_user($db);

//DBusersテーブル、typeカラムと一致しなかった場合
if(is_admin($user) === false){
  //login.phpにリダイレクト
  redirect_to(LOGIN_URL);
}

//DBitemsテーブルの全ての情報を取得し、変数で出力する
$items = get_all_items($db);

// クリックジャッキング対策
  header('X-FRAME-OPTIONS: DENY');
//定数、/var/www/html/../view/admin_view.phpというドキュメントルートを通り、admin_viewデータを読み取る
include_once VIEW_PATH . '/admin_view.php';
