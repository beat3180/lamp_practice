<?php
///var/www/html/../model/functions.phpというドキュメントルートを通り汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//購入明細をインサートする関数
function insert_purchase_detail($db,$order_id,$item_id,$price,$amount){
      $sql = "
        INSERT INTO
          purchase_details(
            order_id,
            item_id,
            price,
            amount
          )
        VALUES(?,?,?,?);
      ";
      //実行した結果を返す
      return execute_query($db, $sql,[$order_id,$item_id,$price,$amount]);
}

//注文番号とそれぞれの商品名、値段、購入数など購入明細に必要な情報を取得する
function get_order_id($db, $order_id){
  $sql = "
    SELECT
      items.item_id,
      items.price,
      carts.cart_id,
      carts.user_id,
      carts.amount,
      historys.order_id
    FROM
      items
    JOIN
      carts
    ON
      items.item_id = carts.item_id
    JOIN
      historys
    ON
      carts.user_id = historys.user_id
    WHERE
      historys.order_id = ?
  ";
  //キーを連番に、値をカラム毎の配列で取得する。
  return fetch_all_query($db, $sql,[$order_id]);
}

function get_user_purchase_details($db, $order_id){
$sql = "
   SELECT
      purchase_details.order_id,
      purchase_details.item_id,
      purchase_details.price,
      purchase_details.amount,
      (purchase_details.price* purchase_details.amount) as sub_total_price,
      items.name
    FROM
     purchase_details
    JOIN
      items
    ON
      purchase_details.item_id = items.item_id
     WHERE
      purchase_details.order_id = ?
  ";
  //キーを連番に、値をカラム毎の配列で取得する。
  return fetch_all_query($db, $sql,[$order_id]);
}



?>
