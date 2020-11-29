<?php
//定数ファイルの読み込み
require_once '../conf/const.php';
///var/www/html/../model/functions.phpというドキュメントルートを通り汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
///var/www/html/../model/user.phpというドキュメントルートを通りuserデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
///var/www/html/../model/item.phpというドキュメントルートを通りitemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';
///var/www/html/../model/history.phpというドキュメントルートを通りhistoryデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'history.php';
///var/www/html/../model/purchase_detail.phpというドキュメントルートを通りpurchase_detailデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'purchase_detail.php';


//セッションの開始、作成
session_start();


//(isset($_SESSION['user_id'])を取得しようとして、取得できなかった場合TRUEを返す
if(is_logined() === false){
  // header関数処理を実行し、login.phpページへリダイレクトする
  redirect_to(LOGIN_URL);
}

//history_view.phpからPOSTで飛んできた特定の$tokenの情報を変数で出力
$token = get_post('csrf');

//CSRF対策のトークンのチェック
if(is_valid_csrf_token($token) === false){
  // header関数処理を実行し、login.phpページへリダイレクトする
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//history_view.phpからPOSTで飛んできた特定の$order_idの情報を変数で出力
$order_id = get_post('order_id');
//history_view.phpからPOSTで飛んできた特定の$create_datetimeの情報を変数で出力
$create_datetime = get_post('create_datetime');
//history_view.phpからPOSTで飛んできた特定の$total_priceの情報を変数で出力
$total_price = get_post('total_price');


//user_idを用いて購入履歴ページに商品を格納、変数として出力
$purchase_details = get_user_purchase_details($db, $order_id);


//定数、/var/www/html/../view/putrchase_detail_view.phpというドキュメントルートを通り、history_viewデータを読み取る
include_once VIEW_PATH . 'purchase_detail_view.php';
