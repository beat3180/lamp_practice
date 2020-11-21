<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//各ユーザーそれぞれのカートページに商品を格納する関数処理
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  $params = array($user_id);
  //キーを連番に、値をカラム毎の配列で取得する。
  return fetch_all_query($db, $sql,$params);
}

//カートに関する一つの商品の情報を抽出する関数
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  //キーをカラム毎に、値をそれぞれのカラムに充てた配列で取得する。
  return fetch_query($db, $sql,$user_id,$item_id);

}

//index.phpからカートに商品を登録する関数。既に登録されていた場合、数を1増やす
function add_cart($db, $user_id, $item_id ) {
  //カートに関するの一つの商品の情報を抽出する関数
  $cart = get_user_cart($db, $user_id, $item_id);
  //テーブルに情報がなかった場合
  if($cart === false){
    //DBcartsテーブルに情報を登録する
    return insert_cart($db, $user_id, $item_id);
  }
  //カート内に情報がすでにあった場合、amountに数を1+した上でアップデートする
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

//DBcartsテーブル、カート内に商品を登録する関数
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?,?,?)
  ";
  //配列でまとめる
  $params = array($item_id,$user_id,$amount);
//実行した結果を返す
  return execute_query($db, $sql,$params);
}

//DBcartテーブル、cart_idで特定のamountカラムを抽出してアップデートする
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  //配列でまとめる
  $params = array($amount,$cart_id);
  //実行した結果を返す
  return execute_query($db, $sql,$params);
}

//DBcartテーブル、cart_idで特定のカラムを抽出してデリートする
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  $params = array($cart_id);
//実行した結果を返す
  return execute_query($db, $sql,$params);
}

//商品購入。エラー処理、DBitemsのstockカラムアップデート処理、DBcartsテーブルの削除処理を通す
function purchase_carts($db, $carts){
  //エラー処理、falseがあった場合
  if(validate_cart_purchase($carts) === false){
    //falseを返す
    return false;
  }
  //carts変数内をforeachで回す
  foreach($carts as $cart){
    //DBitemsテーブル、stockカラムを$cart['amount']の数だけ減らした数へアップデート
    if(update_item_stock(
        $db,
        $cart['item_id'],
        $cart['stock'] - $cart['amount']
      ) === false){
        //失敗した場合のエラー処理
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }

  //DBcartsテーブル、特定のuser_idで抽出したカラムを削除
  delete_user_carts($db, $carts[0]['user_id']);
}

//DBcartsテーブル、特定のuser_idで抽出したカラムを削除
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";
  $params = array($user_id);
//実行した結果を返す
  execute_query($db, $sql,$params);
}


//カート内の商品の合計を計算する関数
function sum_carts($carts){
  //合計の変数を作る
  $total_price = 0;
  //カート内にある商品の配列をforeachで回す
  foreach($carts as $cart){
    //合計の変数にカート商品の値段と数をかけたものを入れる
    $total_price += $cart['price'] * $cart['amount'];
  }
  //値段の合計を返す
  return $total_price;
}

//carts変数内のエラー処理
function validate_cart_purchase($carts){
  //carts変数に何も入っていなかった場合
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    //falseを返す
    return false;
  }
  //carts変数内をforeachで回す
  foreach($carts as $cart){
    //商品のステータスがopenではない
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    //carts変数内の特定の商品の在庫がカートに入れた量よりも少ない場合
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  //エラーが一つ以上あった場合
  if(has_error() === true){
    //falseを返す
    return false;
  }
  //エラーが無い場合trueを返す
  return true;
}
