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

//CSRFトークンの生成、セッションに登録する
$token = get_csrf_token();

//(isset($_SESSION['user_id'])を取得しようとして、取得できなかった場合TRUEを返す
if(is_logined() === false){
  // header関数処理を実行し、login.phpページへリダイレクトする
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//$_SESSION['user_id']でDBusersテーブルから該当するuser_idを抽出し、情報を返す
$user = get_login_user($db);

//各ユーザーそれぞれの購入履歴ページの情報を取得する関数処理、管理者は全ユーザーのページを見れる
$historys = get_user_historys($db,$user,$user['user_id']);

//定数、/var/www/html/../view/history_view.phpというドキュメントルートを通り、history_viewデータを読み取る
include_once VIEW_PATH . 'history_view.php';
