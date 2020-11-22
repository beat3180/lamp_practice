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

//セッションの開始、作成
session_start();

//CSRFトークンの生成、セッションに登録する
$token = get_csrf_token();

//(isset($_SESSION['user_id'])を取得しようとして、取得できなかった場合TRUEを返す
if(is_logined() === false){
  // header関数処理を実行し、login.phpページへリダイレクトする
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//$_SESSION['user_id']を取得する
$user = get_login_user($db);

//user_idを用いてカートページに商品を格納、変数として出力
$carts = get_user_carts($db, $user['user_id']);

//カート内の商品の合計を計算する関数
$total_price = sum_carts($carts);

//定数、/var/www/html/../view/cart_view.phpというドキュメントルートを通り、cart_viewデータを読み取る
include_once VIEW_PATH . 'cart_view.php';
