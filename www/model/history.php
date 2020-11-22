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

?>
