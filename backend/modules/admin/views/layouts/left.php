<?php

use yii\bootstrap\Nav;
use sergmoro1\lookup\models\Lookup;

$user = Yii::$app->user->identity;
?>

<aside class="main-sidebar">

    <section class="sidebar">
        <?php if(!($guest = Yii::$app->user->isGuest)): ?>
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
        <?php endif; ?>
        <?=\yiister\adminlte\widgets\Menu::widget(
            [
                'encodeLabels' => false,
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
                'activeCssClass' => 'active menu-open',
                'activateParents'=>true,
                'items' => [
                    ['label' => Yii::t('app', 'Dashboard'), 'url' => ['site/index'], 'icon' => 'home', 'visible' => !$guest],
                    ['label' => Yii::t('app', 'Login'), 'url' => ['site/login'], 'icon' => 'sign-in', 'visible' => $guest],
                    [
                        'label' => Yii::t('app', 'Auctions'), 
                        'visible' => !$guest,
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
                        'visible' => !$guest,
                    ],
                    [
                        'label' => Yii::t('app', 'Reports'), 
                        'url' => ['report/index'], 
                        'icon' => 'book',
                        'visible' => !$guest,
                    ],
                    [
                        'label' => Yii::t('app', 'Orders'), 
                        'url' => ['order/index'], 
                        'icon' => 'shopping-cart',
                        'visible' => !$guest,
                    ],
                    [
                        'label' => Yii::t('app', 'Owners'), 
                        'url' => ['owner/index'], 
                        'icon' => 'bank',
                        'options' => ['class' => 'treeview'],
                        'visible' => !$guest,
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
                        'visible' => !$guest,
                    ],
                    [
                        'label' => Yii::t('app', 'Inbox'), 
                        'url' => ['invoice/index'],
                        'icon' => 'inbox',
                        'options' => ['class' => 'treeview'],
                        'visible' => !$guest,
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Invoice'),
                                'url' => ['invoice/index'],
                                'icon' => 'sticky-note-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Subscription'), 
                                'url' => ['subscription/index'], 
                                'icon' => 'ticket',
                            ],
                            [
                                'label' => Yii::t('app', 'Purchase'), 
                                'url' => ['purchase/index'], 
                                'icon' => 'book',
                            ],
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Tariffs'), 
                        'url' => ['tariff/index'], 
                        'icon' => 'ticket',
                        'options' => ['class' => 'treeview'],
                        'visible' => !$guest,
                        'items' => [
                            [
                                'label' => Yii::t('app', 'List'),
                                'url' => ['tariff/index'],
                                'icon' => 'list-ul',
                            ],
                            [
                                'label' => Yii::t('app', 'Add'),
                                'url' => ['tariff/create'],
                                'icon' => 'plus',
                            ],
                        ]
                    ],
                ],
            ]
        );
        ?>
    </section>

</aside>
