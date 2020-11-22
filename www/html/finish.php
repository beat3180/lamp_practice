<?php
//定数ファイルの読み込み
require_once '../conf/const.php';
///var/www/html/../model/functions.phpというドキュメントルートを通り汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
///var/www/html/../model/user.phpというドキュメントルートを通りuserデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
///var/www/html/../model/item.phpというドキュメントルートを通りitemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';
///var/www/html/../model/cart.phpというドキュメントルートを通りcartデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'cart.php';
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

//cart_view.phpからPOSTで飛んできた特定の$tokenの情報を変数で出力
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

//user_idを用いてフィニッシュカートページに商品を格納、変数として出力
$carts = get_user_carts($db, $user['user_id']);

//商品購入処理。エラー処理、DBitemsのstockカラムアップデート処理、DBcartsテーブルの削除処理を通す
if(purchase_carts($db, $carts) === false){
  //失敗した場合のエラー処理
  set_error('商品が購入できませんでした。');
  //cart.phpにリダイレクトする
  redirect_to(CART_URL);
}

//カート内の商品の合計を計算する関数
$total_price = sum_carts($carts);

//定数、/var/www/html/../view/finish_view.phpというドキュメントルートを通り、finish_viewデータを読み取る
include_once '../view/finish_view.php';
