<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

//DBitemsテーブルからitem_idで該当する情報を抽出し、返す
function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id,
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = {$item_id}
  ";
//キーをカラム毎に、値をそれぞれのカラムに充てた配列で取得する。
  return fetch_query($db, $sql);
}

//DBitemsテーブルにある情報をtrue・falseで分岐させつつ全て開示する
function get_items($db, $is_open = false){
  $sql = '
    SELECT
      item_id,
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  //trueの引数が入っていた場合、ステータス1のみ開示する
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

//キーを連番に、値をカラム毎の配列で取得する。
  return fetch_all_query($db, $sql);
}

//DBitemsテーブルのステータスに関わらず全ての情報を開示する
function get_all_items($db){
  return get_items($db);
}

//DBitemsテーブルのステータス1=openのみの情報を開示する
function get_open_items($db){
  return get_items($db, true);
}

//画像ファイルの処理やエラー処理を通し、最終的に商品を登録する
function regist_item($db, $name, $price, $stock, $status, $image){
  //画像ファイルがアップロードされた時の関数の処理
  $filename = get_upload_filename($image);
  //エラー関数処理の結果falseが返ってきた場合
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  //トランザクションを絡めて商品を登録する関数を返す
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

//トランザクションを絡めて商品を登録する関数
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  //トランザクションを開始
  $db->beginTransaction();
  //アイテムをインサートする際の処理関数かつ、アップロードした画像ファイルをimagesフォルダに移動させる関数
  if(insert_item($db, $name, $price, $stock, $filename, $status)
    && save_image($image, $filename)){
      //結果をコミットする
    $db->commit();
    //trueを返す
    return true;
  }
  //失敗した場合ロールバックする
  $db->rollback();
  //falseを返す
  return false;

}

//商品をitemsテーブルにインサートする際の処理関数
function insert_item($db, $name, $price, $stock, $filename, $status){
  //定数のキーと受け取ったステータスの値が一致した情報を変数で出力
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  //SQL文の処理
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES('{$name}', {$price}, {$stock}, '{$filename}', {$status_value});
  ";

  //実行した結果を返す
  return execute_query($db, $sql);
}

//DBitemsテーブル、item_idで抽出した該当のstatusをアップデートし、情報を返す
function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = {$status}
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";

  //実行した結果を返す
  return execute_query($db, $sql);
}

//DBitemsテーブル、item_idで抽出した該当のstockをアップデートし、情報を返す
function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = {$stock}
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
//実行した結果を返す
  return execute_query($db, $sql);
}

//DBitemsテーブル、item_idで抽出した該当のカラムを抽出し、デリートする
function destroy_item($db, $item_id){
//特定のitem_idでDBから情報を抽出する
  $item = get_item($db, $item_id);
  //item情報を抽出できなかった場合、falseを返す
  if($item === false){
    return false;
  }
  //トランザクションを開始する
  $db->beginTransaction();
//抽出したimte_idによってitemを削除し、画像も削除する
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
      //結果をコミットする
    $db->commit();
    //trueを返す
    return true;
  }
  //処理が失敗した場合ロールバックする
  $db->rollback();
  return false;
}

//DBitemsテーブルから特定のitem_idで抽出したカラムをデリートするSQL文
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";

  //実行した結果を返す
  return execute_query($db, $sql);
}


// 非DB

//商品のステータスが1=openであるものを返す
function is_open($item){
  return $item['status'] === 1;
}

//エラー処理をまとめた関数
function validate_item($name, $price, $stock, $filename, $status){
  //それぞれの関数処理の結果を変数で出力する
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  //変数の結果を返す
  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

//商品名関連のエラー処理
function is_valid_item_name($name){
  $is_valid = true;
  //定数の設定より、名前の文字数が1文字以上100文字以下に設定され、それが異なる場合
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    //$_SESSION['__errors'][]に'商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。'というメッセージを格納する
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  //trueかfalse、if文で分岐させたいずれかの値を返す
  return $is_valid;
}

//値段関連のエラー処理
function is_valid_item_price($price){
  $is_valid = true;
  //設定した正規表現とマッチしなかった場合
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  //trueかfalse、if文で分岐させたいずれかの値を返す
  return $is_valid;
}

//在庫関連のエラー処理
function is_valid_item_stock($stock){
  $is_valid = true;
  //設定した正規表現とマッチしなかった場合
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  //trueかfalse、if文で分岐させたいずれかの値を返す
  return $is_valid;
}

//画像ファイルのバリデーション処理
function is_valid_item_filename($filename){
  $is_valid = true;
  //ファイルに何も入っていない場合
  if($filename === ''){
    $is_valid = false;
  }
   //trueかfalse、if文で分岐させたいずれかの値を返す
  return $is_valid;
}

//ステータスのバリデーション処理
function is_valid_item_status($status){
  $is_valid = true;
  //ステータスの値と定数が一致しない場合
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  //trueかfalse、if文で分岐させたいずれかの値を返す
  return $is_valid;
}
