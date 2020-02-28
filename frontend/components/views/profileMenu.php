<?php

use yii\helpers\Url;
?>

<nav class="menu-vertical-01 mt-20">

  <ul>
    <!-- <li <?= ($page == 'profile') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/index']) ?>">Профиль</a></li> -->
    <li <?= ($page == 'wishlist') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/wish_list']) ?>">Избранные</a></li>
    <? if (Yii::$app->user->identity->role == 'agent' && Yii::$app->user->identity->ownerId == 2) { ?>
      <li <?= ($page == 'getbankrupt') ? 'class="dropdown-btn active" ' : 'class="dropdown-btn"' ?>><a href="<?= Url::to(['user/get-arrest-bankrupt']) ?>">Расширенный поиск имущества</a></li>
    <? } ?>
    <? if (Yii::$app->user->identity->role == 'agent') { ?>
      <li <?= (strpos($page, 'lot') !== false) ? 'class="dropdown-btn active" ' : 'class="dropdown-btn"' ?>><a href="<?= Url::to(['user/lots']) ?>">Мои лоты</a></li>
      <ul <?= (strpos($page, 'lot') !== false) ? 'class="dropdown-list open" ' : 'class="dropdown-list"' ?>>
        <li <?= ($page == 'lots') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/lots']) ?>">Все лоты</a></li>
        <li <?= ($page == 'importlots') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/import-lots']) ?>">Импортировать лоты</a></li>
        <!-- <li <?= ($page == 'addlot') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/add-lot']) ?>">Добавить лот</a></li> -->
      </ul>

    <? } ?>
    <li <?= ($page == 'setting') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/setting']) ?>">Настройки</a></li>
    <? if (Yii::$app->user->identity->role !== 'user') { ?>
      <li><a href="<?= Yii::$app->params['backLink'].'/login?token='.Yii::$app->user->identity->auth_key ?>" target="_blank">Панель управления</a></li>
    <? } ?>
    <li><a href="<?= Url::to(['site/logout']) ?>">Выйти</a></li>
  </ul>
</nav>