<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
///var/www/html/../model/functions.phpというドキュメントルートを通り汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
///var/www/html/../model/user.phpというドキュメントルートを通りuserデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
///var/www/html/../model/user.phpというドキュメントルートを通りitemデータに関する関数ファイルを読み込み
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
is_valid_csrf_token($token);

//DB接続
$db = get_db_connect();

//$_SESSION['user_id']を取得する
$user = get_login_user($db);

//DBusersテーブル、typeカラムと一致しなかった場合
if(is_admin($user) === false){
   //login.phpにリダイレクト
  redirect_to(LOGIN_URL);
}

//admin_view.phpからPOSTで飛んできた特定のitem_idの情報を変数で出力
$item_id = get_post('item_id');
//admin_view.phpからPOSTで飛んできた特定のstockの情報を変数で出力する
$stock = get_post('stock');

//DBitemsテーブル、stockカラムをアップデート
if(update_item_stock($db, $item_id, $stock)){
  //$_SESSION['__messages'][]に在庫数を変更しました。というメッセージを格納する
  set_message('在庫数を変更しました。');
  //何らかの原因でアップデートに失敗した場合
} else {
   //$_SESSION['__errors'][]に在庫数の変更に失敗しました。というメッセージを格納する
  set_error('在庫数の変更に失敗しました。');
}

//このページが表示されないよう、admin.phpにリダイレクトする
redirect_to(ADMIN_URL);
