<?php
use yii\bootstrap\Nav;
use backend\models\UserAccess;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="https://ei.ru<?= Yii::$app->user->identity->avatar ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=(Yii::$app->user->identity->info['firstname'])? Yii::$app->user->identity->info['firstname'].' '.Yii::$app->user->identity->info['lastname'] : Yii::$app->user->identity->username?></p>

                <a href="#"><?= Yii::$app->params['role'] ?></a>
            </div>
        </div>

        <!-- search form -->
        <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form> -->
        <!-- /.search form -->

        <?=\yiister\adminlte\widgets\Menu::widget(
            [
                'encodeLabels' => false,
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
                'activeCssClass' => 'active menu-open',
                'activateParents'=>true,
                'items' => [
                    // ['label' => 'Меню', 'options' => ['class' => 'header'], 'template' => '{label}'],
                    // ['label' => 'Главная', 'url' => ['site/index'], 'icon' => 'home'],
                    [
                        'label' => 'Лоты', 
                        'url' => ['lots/index'], 
                        'icon' => 'table',
                        'visible' => UserAccess::forManager('lots'),
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            [
                                'label' => 'Список лотов',
                                'url' => ['lots/index'],
                                'icon' => 'list-ul',
                                'visible' => UserAccess::forManager('lots')
                            ],
                            [
                                'label' => 'Добавить лот',
                                'url' => ['lots/add'],
                                'icon' => 'plus',
                                'visible' => UserAccess::forManager('lots', 'add')
                            ],
                        ]

                    ],
                    [
                        'label' => 'Пользователи', 
                        'url' => ['users/index'], 
                        'icon' => 'users',
                        'visible' => UserAccess::forAdmin('users'),

                    ],
                    [
                        'label' => 'Gii', //for basic
                        'url' => ['/gii'],
                        'icon' => 'file-code-o',
                        'visible' => UserAccess::forSuperAdmin('debug')
                    ],
                    [
                        'label' => 'Debug', //for basic
                        'url' => ['/debug'],
                        'icon' => 'dashboard',
                        'visible' => UserAccess::forSuperAdmin('debug')
                    ],
                    [
                        'label' => 'Вход', //for basic
                        'url' => ['/site/login'],
                        'icon' => 'dashboard',
                        'visible' => Yii::$app->user->isGuest
                    ],
                    [
                        'label' => 'Другие инструменты',
                        'icon' => 'share',
                        'url' => '#',
                        'visible' => UserAccess::forSuperAdmin('debug'),
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );
        ?>

    </section>

</aside>
