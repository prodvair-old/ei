<?php

use yii\helpers\Url;
?>

<nav class="menu-vertical-01 mt-20">

  <ul>
    <?= $page; ?>
    <li <?= ($page == 'profile') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/index']) ?>">Профиль</a></li>
    <li <?= ($page == 'wishlist') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/wish_list']) ?>">Избранные</a></li>
    <? if (Yii::$app->user->identity->role == 'agent') { ?>
      <li <?= (strpos($page, 'lot') !== false) ? 'class="dropdown-btn active" ' : 'class="dropdown-btn"' ?>><a href="javascript:void(0);">Мои лоты</a></li>
      <ul <?= (strpos($page, 'lot') !== false) ? 'class="dropdown-list open" ' : 'class="dropdown-list"' ?>>
        <li <?= ($page == 'editlot') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/editlot']) ?>">Добавить лот</a></li>
        <li <?= ($page == 'addlots') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/addlots']) ?>">Импортировать лоты из CSV</a></li>
        <li <?= ($page == 'lots') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/lots']) ?>">Все лоты</a></li>
      </ul>

    <? } ?>
    <li <?= ($page == 'setting') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/setting']) ?>">Настройки</a></li>
    <li><a href="<?= Url::to(['site/logout']) ?>">Выйти</a></li>
  </ul>
</nav>