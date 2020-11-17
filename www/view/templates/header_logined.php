<header>
  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <!--index.phpのドキュメントルート-->
    <a class="navbar-brand" href="<?php print(HOME_URL);?>">Market</a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#headerNav" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="ナビゲーションの切替">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="headerNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <!--cart.phpのドキュメントルート-->
          <a class="nav-link" href="<?php print(CART_URL);?>">カート</a>
        </li>
        <li class="nav-item">
          <!--logout.phpのドキュメントルート-->
          <a class="nav-link" href="<?php print(LOGOUT_URL);?>">ログアウト</a>
        </li>
         <!--adminユーザーの場合、下を表示-->
        <?php if(is_admin($user)){ ?>
          <li class="nav-item">
            <!--admin.phpのドキュメントルート-->
            <a class="nav-link" href="<?php print(ADMIN_URL);?>">管理</a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </nav>
  <p>ようこそ、<?php print($user['name']); ?>さん。</p>
</header>
