<?php

use yii\bootstrap\Nav;
use sergmoro1\lookup\models\Lookup;

$user = common\models\db\User::findOne(['username' => 'sergey@vorst.ru']);
//$user = Yii::$app->user->identity;
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?= $user->getAvatar(['class' => 'img-circle']) ?>
            </div>
            <div class="pull-left info">
                <p><?= $user->getFullName() ?></p>

                <a href="#"><?= Lookup::item('UserRole', $user->role) ?></a>
            </div>
        </div>

        <?=\yiister\adminlte\widgets\Menu::widget(
            [
                'encodeLabels' => false,
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
                'activeCssClass' => 'active menu-open',
                'activateParents'=>true,
                'items' => [
                    ['label' => 'Главная', 'url' => ['site/index'], 'icon' => 'home'],
                    [
                        'label' => 'Лоты', 
                        'url' => ['lot/index'], 
                        'icon' => 'table',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            [
                                'label' => 'Список',
                                'url' => ['lot/index'],
                                'icon' => 'list-ul',
                            ],
                            [
                                'label' => 'Добавить',
                                'url' => ['lot/create'],
                                'icon' => 'plus',
                            ],
                        ]

                    ],
                    [
                        'label' => 'Организации', 
                        'url' => ['owner/index'], 
                        'icon' => 'table',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            [
                                'label' => 'Список',
                                'url' => ['owner/index'],
                                'icon' => 'list-ul',
                            ],
                            [
                                'label' => 'Добавить',
                                'url' => ['owner/create'],
                                'icon' => 'plus',
                            ],
                        ]

                    ],
                    [
                        'label' => 'Пользователи', 
                        'url' => ['user/index'], 
                        'icon' => 'users',

                    ],
                ],
            ]
        );
        ?>
    </section>

</aside>
