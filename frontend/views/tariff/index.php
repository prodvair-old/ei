<?php

use common\models\db\Tariff;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $tariff Tariff */

$this->title = 'Тарифы';
$this->params['breadcrumbs'] = Yii::$app->params['breadcrumbs'];

?>

<section class="page-wrapper page-detail">

    <div class="page-title bg-light d-none d-sm-block">

        <div class="container">

            <div class="row gap-15 align-items-center">

                <div class="col-12">

                    <nav aria-label="breadcrumb">
                        <?= Breadcrumbs::widget([
                            'itemTemplate'       => '<li class="breadcrumb-item">{link}</li>',
                            'encodeLabels'       => false,
                            'tag'                => 'ol',
                            'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                            'homeLink'           => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
                            'links'              => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </nav>

                </div>

            </div>

        </div>

    </div>

    <div class="container pt-30">


        <div class="row gap-20 gap-lg-40" itemscope itemtype="http://schema.org/Product">

            <div class="col-12 col-lg-8">

                <div class="content-wrapper">

                    <div id="desc" class="detail-header mb-30">
                        <h1 class="h3 lh-h1 mt-5" itemprop="name"
                            style="max-height: 130px;overflow: hidden;"><?= $this->title ?></h1>

                        <div class="d-flex flex-row align-items-sm-center mb-20">
                            <? if (!Yii::$app->user->isGuest): ?>

                            <?php else: ?>

                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="mt-50"></div>
                    <div id="info" class="fullwidth-horizon--section">

                        <h4 class="heading-title">Тарифы</h4>

                        <?php
                        echo "<pre>";
                        var_dump($paymentStatus);
                        echo "</pre>";
                        ?>

                        <ul class="list-icon-absolute what-included-list row">
                            <?php foreach ($tariff as $item): ?>
                                <li class="col-12 col-md-6 pl-15 pr-15">
                                    <figure class="tour-grid-item-01 box-shadow borr-10">
                                        <a href="<?= Url::to(['/bankrupt']) . '/' . '' ?>">

                                            <figcaption class="content">
                                                <div class="lot__block__info__content__offer"></div>
                                                <h5><?= $item->name ?></h5>
                                                <ul class="item-meta mt-10 pl-0">
                                                    <li class="pl-0">
                                                        <span class="font500"></span> <?= $item->description ?>
                                                    </li>
                                                    <li class="pl-0">
                                                        <span class="font500"><h4> <?= $item->fee ?> ₽</h4></span>
                                                    </li>
                                                </ul>

                                                <small class="text-green font600">Приобрести<i
                                                            class="fa fa-arrow-right"></i></small>
                                            </figcaption>
                                        </a>
                                    </figure>

                                </li>

                            <?php endforeach; ?>

                            <form action="/tariff" method="post">
                                <button type="submit">pay</button>
                            </form>


                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
