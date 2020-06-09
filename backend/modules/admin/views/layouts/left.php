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
                        'label' => Yii::t('app', 'Auctions'), 
                        'url' => ['torg/index'], 
                        'icon' => 'gavel',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            [
                                'label' => Yii::t('app', 'List'),
                                'url' => ['torg/index'],
                                'icon' => 'list-ul',
                            ],
                            [
                                'label' => Yii::t('app', 'Add'),
                                'url' => ['torg/create'],
                                'icon' => 'plus',
                            ],
                        ]

                    ],
                    [
                        'label' => Yii::t('app', 'Lots'), 
                        'url' => ['lot/index'], 
                        'icon' => 'money',
                    ],
                    [
                        'label' => Yii::t('app', 'Reports'), 
                        'url' => ['report/index'], 
                        'icon' => 'book',
                    ],
                    [
                        'label' => Yii::t('app', 'Owners'), 
                        'url' => ['owner/index'], 
                        'icon' => 'bank',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            [
                                'label' => Yii::t('app', 'List'),
                                'url' => ['owner/index'],
                                'icon' => 'list-ul',
                            ],
                            [
                                'label' => Yii::t('app', 'Add'),
                                'url' => ['owner/create'],
                                'icon' => 'plus',
                            ],
                        ]

                    ],
                    [
                        'label' => Yii::t('app', 'Users'), 
                        'url' => ['user/index'], 
                        'icon' => 'users',

                    ],
                ],
            ]
        );
        ?>
    </section>

</aside>
