<!DOCTYPE html>
<html lang="ja">
<head>
<!--//定数、/var/www/html/../view/templates/head.phpというドキュメントルートを通り、head.phpデータを読み取る-->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
</head>
<body>
<!--//定数、/var/www/html/../view/templates/header_logined.phpというドキュメントルートを通り、header_logined.phpデータを読み取る-->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

<!--//定数、/var/www/html/../view/templates/messages.phpというドキュメントルートを通り、messages.phpデータを読み取る-->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

<!--$cartsに一つ以上値が入っていた場合は表示される-->
    <?php if(count($historys) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>注文の合計金額</th>
            <th>購入明細</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($historys as $history){ ?>
          <tr>
            <td><?php print ($history['order_id']); ?></td>
            <td><?php print ($history['create_datetime']); ?></td>
            <td><?php print number_format($history['total_price']); ?></td>
            <form method="post" action="purchase_detail.php">
                <td><input type="submit" value="購入明細" class="btn btn-secondary"></td>
                <input type="hidden" name="order_id" value="<?php print ($history['order_id']); ?>">
                <input type="hidden" name="create_datetime" value="<?php print ($history['create_datetime']); ?>">
                <input type="hidden" name="total_price" value="<?php print ($history['total_price']); ?>">
                <!--CSRF対策のセッションに登録されたトークンを送信する-->
                <input type="hidden" name="csrf" value="<?php print($token); ?>">
            </form>
          </tr>
          <?php } ?>
        </tbody>
      </table>
       <!--$historysに何も値が入っていない場合-->
    <?php } else { ?>
      <p>購入履歴はありません。</p>
    <?php } ?>
  </div>
</body>
</html>
