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
function fetch_query($db, $sql,...$params_array){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
     // ----- パラメータを一つの配列に統合 ----- //
    $integrated_params = array();
    $index = 1;
    foreach ($params_array as $params) {
        // $paramがスカラまたはnullの時は配列に変換
        if (is_scalar($params) || $params === null) {
            $params = array($params);
        }
        foreach ($params as $param_id => $value) {
            // 数値添字のときは疑問符パラメータとみなす
            if (gettype($param_id) == 'integer') {
              // 疑問符パラメータ
                $integrated_params[$index] = $value;
            } else {
              // 名前付きパラメータ
                $integrated_params[$param_id] = $value;
            }
            $index++;
        }
    }

     // ----- データ型に応じてバインド ----- //
    foreach ($integrated_params as $param_id => $value) {
        switch (gettype($value)) {
          //論理型
            case 'boolean':
                $param_type = PDO::PARAM_BOOL;
                break;
            //整数型
            case 'integer':
                $param_type = PDO::PARAM_INT;
                break;
                //浮動小数点数
            case 'double':
                $param_type = PDO::PARAM_STR;
                break;
                //文字列型
            case 'string':
                $param_type = PDO::PARAM_STR;
                break;

            case 'NULL':
                $param_type = PDO::PARAM_NULL;
                break;
            //当てはまるデータ型がなければdefaultへ
            default:
                $param_type = PDO::PARAM_STR;
        }

        $statement->bindValue($param_id, $value, $param_type);
    }
       // SQLを実行
        $statement->execute();
      //結果を返す
        return $statement->fetch();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

//キーを連番に、値をカラム毎の配列で取得する。失敗した場合はfalseを返す
function fetch_all_query($db, $sql,...$params_array){
   try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
     // ----- パラメータを一つの配列に統合 ----- //
    $integrated_params = array();
    $index = 1;
    foreach ($params_array as $params) {
        // $paramがスカラまたはnullの時は配列に変換
        if (is_scalar($params) || $params === null) {
            $params = array($params);
        }
        foreach ($params as $param_id => $value) {
            // 数値添字のときは疑問符パラメータとみなす
            if (gettype($param_id) == 'integer') {
              // 疑問符パラメータ
                $integrated_params[$index] = $value;
            } else {
              // 名前付きパラメータ
                $integrated_params[$param_id] = $value;
            }
            $index++;
        }
    }

     // ----- データ型に応じてバインド ----- //
    foreach ($integrated_params as $param_id => $value) {
        switch (gettype($value)) {
          //論理型
            case 'boolean':
                $param_type = PDO::PARAM_BOOL;
                break;
            //整数型
            case 'integer':
                $param_type = PDO::PARAM_INT;
                break;
                //浮動小数点数
            case 'double':
                $param_type = PDO::PARAM_STR;
                break;
                //文字列型
            case 'string':
                $param_type = PDO::PARAM_STR;
                break;

            case 'NULL':
                $param_type = PDO::PARAM_NULL;
                break;
            //当てはまるデータ型がなければdefaultへ
            default:
                $param_type = PDO::PARAM_STR;
        }

        $statement->bindValue($param_id, $value, $param_type);
    }
       // SQLを実行
        $statement->execute();
      //結果を返す
        return $statement->fetchAll();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}




//bindValueを利用したSQL文を実行する。失敗した場合はfalseを返す
function execute_query($db, $sql, ...$params_array){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
     // ----- パラメータを一つの配列に統合 ----- //
    $integrated_params = array();
    $index = 1;
    foreach ($params_array as $params) {
        // $paramがスカラまたはnullの時は配列に変換
        if (is_scalar($params) || $params === null) {
            $params = array($params);
        }
        foreach ($params as $param_id => $value) {
            // 数値添字のときは疑問符パラメータとみなす
            if (gettype($param_id) == 'integer') {
              // 疑問符パラメータ
                $integrated_params[$index] = $value;
            } else {
              // 名前付きパラメータ
                $integrated_params[$param_id] = $value;
            }
            $index++;
        }
    }

     // ----- データ型に応じてバインド ----- //
    foreach ($integrated_params as $param_id => $value) {
        switch (gettype($value)) {
          //論理型
            case 'boolean':
                $param_type = PDO::PARAM_BOOL;
                break;
            //整数型
            case 'integer':
                $param_type = PDO::PARAM_INT;
                break;
                //浮動小数点数
            case 'double':
                $param_type = PDO::PARAM_STR;
                break;
                //文字列型
            case 'string':
                $param_type = PDO::PARAM_STR;
                break;

            case 'NULL':
                $param_type = PDO::PARAM_NULL;
                break;
            //当てはまるデータ型がなければdefaultへ
            default:
                $param_type = PDO::PARAM_STR;
        }

        $statement->bindValue($param_id, $value, $param_type);

    }

    // SQLを実行、結果を返す
    return $statement->execute();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}
