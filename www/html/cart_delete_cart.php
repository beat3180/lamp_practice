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
//$_SESSION['user_id']でDBusersテーブルから該当するuser_idを抽出し、情報を返す
$user = get_login_user($db);

//cart_view.phpからPOSTで飛んできた特定のcart_idの情報を変数で出力する
$cart_id = get_post('cart_id');

//DBcartテーブルのcart_idで抽出されたカラムをデリーとする
if(delete_cart($db, $cart_id)){
  set_message('カートを削除しました。');
} else {
  set_error('カートの削除に失敗しました。');
}

//このページが表示されないよう、cart.phpにリダイレクトする
redirect_to(CART_URL);
