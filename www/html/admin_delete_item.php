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


//DBitemsテーブル、item_idで抽出したカラムを削除し、画像もフォルダから削除する
if(destroy_item($db, $item_id) === true){
   //$_SESSION['__messages'][]に商品を削除しました。というメッセージを格納する
  set_message('商品を削除しました。');
  //何らかの処理が失敗した場合
} else {
  //$_SESSION['__errors'][]に商品削除に失敗しました。というメッセージを格納する
  set_error('商品削除に失敗しました。');
}


//このページが表示されないよう、admin.phpにリダイレクトする
redirect_to(ADMIN_URL);
