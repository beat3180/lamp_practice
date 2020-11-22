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

//DBitemsテーブルのステータス1=openのみの情報を開示する
$items = get_open_items($db);

//定数、/var/www/html/../view/index_view.phpというドキュメントルートを通り、index_viewデータを読み取る
include_once VIEW_PATH . 'index_view.php';
