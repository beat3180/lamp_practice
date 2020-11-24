<?php
///var/www/html/../model/functions.phpというドキュメントルートを通り汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//購入履歴をインサートする関数
function insert_history($db, $user_id){
   $sql = "
    INSERT INTO
      historys(
        user_id
      )
    VALUES(?);
  ";
//実行した結果を返す
  return execute_query($db, $sql,[$user_id]);

}

//各ユーザーそれぞれの購入履歴ページの情報を取得する関数処理、管理者は全ユーザーのページを見れる
function get_user_historys($db,$user,$user_id){

if(is_admin($user)){
  $sql = "
   SELECT
      historys.order_id,
      historys.user_id,
      historys.create_datetime,
      SUM(purchase_details.price*purchase_details.amount)as total_price
    FROM
      historys
    JOIN
      purchase_details
    ON
      historys.order_id = purchase_details.order_id
     GROUP BY
      historys.order_id
    ORDER BY
     order_id DESC
  ";

  //キーを連番に、値をカラム毎の配列で取得する。
  return fetch_all_query($db, $sql);

  }else{


    $sql = "
    SELECT
        historys.order_id,
        historys.user_id,
        historys.create_datetime,
        SUM(purchase_details.price*purchase_details.amount)as total_price
      FROM
        historys
      JOIN
        purchase_details
      ON
        historys.order_id = purchase_details.order_id
      WHERE
        historys.user_id = ?
      GROUP BY
        historys.order_id
      ORDER BY
      order_id DESC
    ";

  //キーを連番に、値をカラム毎の配列で取得する。
  return fetch_all_query($db, $sql,[$user_id]);

  }
}


?>
