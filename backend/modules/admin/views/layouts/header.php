<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use sergmoro1\lookup\models\Lookup;

use common\models\db\User;

/* @var $this \yii\web\View */
/* @var $content string */

$user = common\models\db\User::findOne(['username' => 'sergey@vorst.ru']);
//$user = Yii::$app->user->identity;
?>

<header class="main-header">

    <?= Html::a(Yii::$app->name, Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Тумблер меню</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="https://ei.ru<?= $user->avatar ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= $user->getFullName() ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $user->avatar ?>" class="img-circle" alt="User Image"/>
                            <p>
                                <?= $user->getFullName() ?>
                                <small><?= Lookup::item('UserRole', $user->group) ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-<?= $user->group == User::GROUP_ADMIN ? '6' : '12' ?> text-center">
                                <a href="<?= Url::to(['historys/index']) ?>">История</a>
                            </div>
                            <?php if ($user->group == User::GROUP_ADMIN): ?>
                            <div class="col-xs-4 text-center">
                                <a href="<?= Url::to(['historys/all']) ?>">Журнал</a>
                            </div>
                            <?php endif; ?>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="http://ei.ru/profile" class="btn btn-default btn-flat">Профиль</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Выйти',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>