<?php
//定数ファイルの読み込み
require_once '../conf/const.php';
///var/www/html/../model/functions.phpというドキュメントルートを通り汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
///var/www/html/../model/user.phpというドキュメントルートを通りuserデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

//セッションの開始、作成
session_start();

//(isset($_SESSION['user_id'])を取得できた場合TRUEを返す
if(is_logined() === true){
   // header関数処理を実行し、index.phpページへリダイレクトする
  redirect_to(HOME_URL);
}

//signup_view.phpから飛んできたname情報を変数で出力する
$name = get_post('name');
//signup_view.phpから飛んできたpassword情報を変数で出力する
$password = get_post('password');
//signup_view.phpから飛んできたpassword_confirmation情報を変数で出力する
$password_confirmation = get_post('password_confirmation');

//DB接続
$db = get_db_connect();

//try構文。ブロック内で例外が起きればcatchで捕まえ、異常処理を出力する
try{

  //ユーザー名とパスワードのエラー処理を行い、間違っていた場合falseを返す
  $result = regist_user($db, $name, $password, $password_confirmation);
  //signup.phpにリダイレクトする
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  //例外が起きた場合それを捕まえ、エラーを表示した上でsignup.phpにリダイレクトする
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}

set_message('ユーザー登録が完了しました。');
//入力された情報をDBuserテーブルと照合、user_idをセッションに登録しユーザー情報を返す関数
login_as($db, $name, $password);
//index.phpへリダイレクトする
redirect_to(HOME_URL);
