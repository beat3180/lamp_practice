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
//admin_view.phpからPOSTで飛んできた特定のchanges_toの情報を変数で出力する
$changes_to = get_post('changes_to');

//changes_toで出力された情報がopenの場合
if($changes_to === 'open'){
//DBitemsテーブル、statusカラムをアップデート。ITEM_STATUS_OPENは1
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  //$_SESSION['__messages'][]にステータスを変更しました。というメッセージを格納する
  set_message('ステータスを変更しました。');
  //changes_toで出力された情報がcloseの場合
}else if($changes_to === 'close'){
  //DBitemsテーブル、statusカラムをアップデート。ITEM_STATUS_CLOSEは0
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  //$_SESSION['__messages'][]にステータスを変更しました。というメッセージを格納する
  set_message('ステータスを変更しました。');
  //changes_toで出力された情報がopenでもcloseでもない場合
}else {
  //$_SESSION['__errors'][]に不正なリクエストです。というエラーメッセージを格納する
  set_error('不正なリクエストです。');
}

//このページが表示されないよう、admin.phpにリダイレクトする
redirect_to(ADMIN_URL);
