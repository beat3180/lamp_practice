<?php
// 定数ファイルの読み込み
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

//admin_view.phpからPOSTで飛んできた特定の$tokenの情報を変数で出力
$token = get_post('csrf');

//CSRF対策のトークンのチェック
if(is_valid_csrf_token($token) === false){
  // header関数処理を実行し、login.phpページへリダイレクトする
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();

//$_SESSION['user_id']を取得する
$user = get_login_user($db);

//DBusersテーブル、typeカラムと一致しなかった場合
if(is_admin($user) === false){
  //login.phpにリダイレクト
  redirect_to(LOGIN_URL);
}

//admin_view.phpからPOSTで飛んできた特定のnameの情報を変数で出力
$name = get_post('name');
//admin_view.phpからPOSTで飛んできた特定のpriceの情報を変数で出力
$price = get_post('price');
//admin_view.phpからPOSTで飛んできた特定のstatusの情報を変数で出力
$status = get_post('status');
//admin_view.phpからPOSTで飛んできた特定のstockの情報を変数で出力
$stock = get_post('stock');

//view_admin.phpから飛んできたimageをグローバル変数FILESに配列として格納し、変数で出力する
$image = get_file('image');

//出力された変数を引数として用い、さまざまな処理を通して商品を登録する
if(regist_item($db, $name, $price, $stock, $status, $image)){
   //$_SESSION['__messages'][]に商品を登録しました。というメッセージを格納する
  set_message('商品を登録しました。');
  //何らかの処理が失敗した場合
}else {
  //$_SESSION['__errors'][]に商品の登録に失敗しました。というメッセージを格納する
  set_error('商品の登録に失敗しました。');
}

//このページが表示されないよう、admin.phpにリダイレクトする
redirect_to(ADMIN_URL);
