<?php

use yii\helpers\Url;
?>

<nav class="menu-vertical-01 mt-20">

  <ul>
    <!-- <li <?= ($page == 'profile') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/index']) ?>">Профиль</a></li> -->
    <li <?= ($page == 'wishlist') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/wish_list']) ?>">Избранные</a></li>
    <li <?= ($page == 'search-preset') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/search-preset']) ?>">Поисковые отслеживания</a></li>
    <?php if (Yii::$app->user->identity->role !== 'user') { ?>
      <?php if (Yii::$app->user->identity->role == 'agent') { ?>
        <li><a href="<?= Yii::$app->params['backLink'].'/login?token='.Yii::$app->user->identity->auth_key ?>&link[to]=find&link[page]=arrest" target="_blank">Расширенный поиск имущества</a></li>
        <li><a href="<?= Yii::$app->params['backLink'].'/login?token='.Yii::$app->user->identity->auth_key ?>&link[to]=lots&link[page]=index" target="_blank">Мои лоты</a></li>
      <?php } else { ?>
        <li><a href="<?= Yii::$app->params['backLink'].'/login?token='.Yii::$app->user->identity->auth_key ?>&link[to]=lots&link[page]=index" target="_blank">Список лотов</a></li>
      <?php } ?>
    <?php } ?>
    <li <?= ($page == 'setting') ? 'class="active"' : '' ?>><a href="<?= Url::to(['user/setting']) ?>">Настройки</a></li>
    <li <?= ($page == 'notification') ? 'class="active"' : '' ?>><a href="<?= Url::to(['profile/notification']) ?>">Уведомления</a></li>
    <?php if (Yii::$app->user->identity->role !== 'user') { ?>
      <li><a href="<?= Yii::$app->params['backLink'].'/login?token='.Yii::$app->user->identity->auth_key ?>" target="_blank">Панель управления</a></li>
    <?php } ?>
    <li><a href="<?= Url::to(['site/logout']) ?>">Выйти</a></li>
  </ul>
</nav>
