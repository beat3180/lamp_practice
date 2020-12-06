<!DOCTYPE html>
<html lang="ja">
<head>
<!--//定数、/var/www/html/../view/templates/head_materialize.phpというドキュメントルートを通り、head_materialize.phpデータを読み取る-->
  <?php include VIEW_PATH . 'templates/head_materialize.php'; ?>
  <title>マンガトーカー</title>
   <!--//定数、/assets/css/materialize.cssというドキュメントルートを通り、materialize.cssを読み込む-->
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'materialize.css'); ?>">
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>

</head>
<style>
h1{
         border-bottom: 1px solid;
}

h3{
        border-bottom: 1px solid;
}
div{
        text-align: center;
        margin: 0 auto;
}
</style>
<body>
<h1>記事投稿サイト・マンガトーカー　ワイヤーフレーム</h1>
<div><a href="portfolios.php"><img src="/assets/images/ポートフォリオサイト.png" width="350" height="200"></a></div>
<h3>コンセプトシート</h3>

<div><a href="/assets/images/マンガトーカー/コンセプトシート.png" data-lightbox="group"><img src="/assets/images/マンガトーカー/コンセプトシート.png" width="650" height="500" alt="コンセプトシート1" title="コンセプトシート1"></a></div>

<div><a href="/assets/images/マンガトーカー/コンセプトシート(1).png" data-lightbox="group"><img src="/assets/images/マンガトーカー/コンセプトシート(1).png" width="650" height="500" alt="コンセプトシート2" title="コンセプトシート2"></a></div>

<div><a href="/assets/images/マンガトーカー/コンセプトシート(2).png" data-lightbox="group"><img src="/assets/images/マンガトーカー/コンセプトシート(2).png" width="650" height="500" alt="コンセプトシート3" title="コンセプトシート3"></a></div>

<h3>サイトマップ</h3>

<div><a href="/assets/images/マンガトーカー/サイトマップ(3).png" data-lightbox="group"><img src="/assets/images/マンガトーカー/サイトマップ(3).png" width="650" height="500" alt="サイトマップ1" title="サイトマップ1"></a></div>

<div><a href="/assets/images/マンガトーカー/サイトマップ(1).png" data-lightbox="group"><img src="/assets/images/マンガトーカー/サイトマップ(1).png" width="650" height="500" alt="サイトマップ2" title="サイトマップ2"></a></div>

<div><a href="/assets/images/マンガトーカー/サイトマップ(2).png" data-lightbox="group"><img src="/assets/images/マンガトーカー/サイトマップ(2).png" width="650" height="500" alt="サイトマップ3" title="サイトマップ3"></a></div>

<h3>ER図</h3>

<div><a href="/assets/images/マンガトーカー/ER1.png" data-lightbox="group"><img src="/assets/images/マンガトーカー/ER1.png" width="500" height="600" alt="ER図" title="ER図"></a></div>

<footer class="page-footer grey darken-2">
    <div class="container">
      <div class="row">
        <div class="col m4 s12">
                <br />
                <br />
        </div>

        <div class="col m4 s12">
                <br />
                <br />
                <br />
        </div>

      </div>
    </div>
</footer>

</body>
</html>
