<?php

//DB接続、設定
function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;

  //try構文。ブロック内で例外が起きればcatchで捕まえ、異常処理を出力する
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    //SQL実行でエラーが起こった際にどう処理するかを指定、ERRMODE_EXCEPTIONを設定すると例外をスロー
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //データベース側が持つ「プリペアドステートメント」という機能のエミュレーションをPDO側で行うかどうかを設定
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //ステートメントがforeach文に直接かけられた場合のフェッチスタイルを設定、FETCH_ASSOCはカラム名をキーとする連想配列で取得する
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  return $dbh;
}

//キーをカラム毎に、値をそれぞれのカラムに充てた配列で取得する。失敗した場合はfalseを返す
function fetch_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQLを実行
    $statement->execute($params);
    //結果を返す
    return $statement->fetch();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

//キーを連番に、値をカラム毎の配列で取得する。失敗した場合はfalseを返す
function fetch_all_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQLを実行
    $statement->execute($params);
    //結果を返す
    return $statement->fetchAll();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

//SQL文を実行する。失敗した場合はfalseを返す
function execute_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQLを実行、結果を返す
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。');
  }
  return false;
}

//ページネーション用、全てのデータ件数を取得する
function fetch_Column_query($db, $sql, $params = array()){
   try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQLを実行
    $statement->execute($params);
    //結果を返す
    return $statement->fetchColumn();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;


}
