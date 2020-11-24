<!DOCTYPE html>
<html lang="ja">
<head>
<!--//定数、/var/www/html/../view/templates/head.phpというドキュメントルートを通り、head.phpデータを読み取る-->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
</head>
<body>
<!--//定数、/var/www/html/../view/templates/header_logined.phpというドキュメントルートを通り、header_logined.phpデータを読み取る-->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

<!--//定数、/var/www/html/../view/templates/messages.phpというドキュメントルートを通り、messages.phpデータを読み取る-->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php print ($order_id); ?></td>
            <td><?php print ($create_datetime); ?></td>
            <td><?php print ($total_price); ?></td>
          </tr>
        </tbody>
     </table>

      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>購入時の商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($purchase_details as $purchase_detail){ ?>
          <tr>
            <td><?php print h(($purchase_detail['name'])); ?></td>
            <td><?php print ($purchase_detail['price']); ?></td>
            <td><?php print ($purchase_detail['amount']); ?></td>
            <td><?php print number_format($purchase_detail['sub_total_price']); ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
  </div>
</body>
</html>
