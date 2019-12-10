<?php

use yii\helpers\Url;
?>

<nav class="menu-vertical-01 mt-20">

  <ul>
    <?= $page; ?>
    <li <?= ($page == 'profile') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/index']) ?>">Профиль</a></li>
    <li <?= ($page == 'wishlist') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/wish_list']) ?>">Избранные</a></li>
    <? if (Yii::$app->user->identity->role == 'agent') { ?>
      <li <?= ($page == 'lots') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/lots']) ?>">Мои лоты</a></li>
      <li <?= ($page == 'addlots') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/addlots']) ?>">Добавить лоты</a></li>
    <? } ?>
    <li <?= ($page == 'setting') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/setting']) ?>">Настройки</a></li>
    <li><a href="<?= Url::to(['site/logout']) ?>">Выйти</a></li>
  </ul>

</nav>