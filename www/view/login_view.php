<!DOCTYPE html>
<html lang="ja">
<head>
  <!--//定数、/var/www/html/../view/templates/head.phpというドキュメントルートを通り、head.phpデータを読み取る-->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>ログイン</title>
  <!--//定数、/assets/css/login.cssというドキュメントルートを通り、login.cssを読み込む-->
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'login.css'); ?>">
</head>
<body>
  <!--//定数、/var/www/html/../view/templates/header.phpというドキュメントルートを通り、header.phpデータを読み取る-->
  <?php include VIEW_PATH . 'templates/header.php'; ?>
  <div class="container">
    <h1>ログイン</h1>

     <!--//定数、/var/www/html/../view/templates/messages.phpというドキュメントルートを通り、messages.phpデータを読み取る-->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <!--form内の情報をlogin_process.phpへ飛ばす-->
    <form method="post" action="login_process.php" class="login_form mx-auto">
      <div class="form-group">
        <label for="name">名前: </label>
        <input type="text" name="name" id="name" class="form-control">
      </div>
      <div class="form-group">
        <label for="password">パスワード: </label>
        <input type="password" name="password" id="password" class="form-control">
      </div>
      <input type="submit" value="ログイン" class="btn btn-primary">
      <!--CSRF対策のセッションに登録されたトークンを送信する-->
      <input type="hidden" name="csrf" value="<?php print($token); ?>">
    </form>
  </div>
</body>
</html>
