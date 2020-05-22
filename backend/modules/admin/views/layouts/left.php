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
                <img src="https://ei.ru<?= $user->avatar ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->getFullName() ?></p>

                <a href="#"><?= Lookup::item('UserRole', $user->group) ?></a>
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
                        'url' => ['lots/index'], 
                        'icon' => 'table',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            [
                                'label' => 'Список лотов',
                                'url' => ['lots/index'],
                                'icon' => 'list-ul',
                            ],
                            [
                                'label' => 'Импортировать лот',
                                'url' => ['lots/import'],
                                'icon' => 'download',
                            ],
                            [
                                'label' => 'Добавить лот',
                                'url' => ['lots/create'],
                                'icon' => 'plus',
                            ],
                        ]

                    ],
                    [
                        'label' => 'Организации', 
                        'url' => ['owners/index'], 
                        'icon' => 'table',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            [
                                'label' => 'Список организации',
                                'url' => ['owners/index'],
                                'icon' => 'list-ul',
                            ],
                            [
                                'label' => 'Добавить организацию',
                                'url' => ['owners/create'],
                                'icon' => 'plus',
                            ],
                        ]

                    ],
                    [
                        'label' => 'Пользователи', 
                        'url' => ['users/index'], 
                        'icon' => 'users',

                    ],
                ],
            ]
        );
        ?>
    </section>

</aside>
