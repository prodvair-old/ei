<?php

use common\models\db\Tariff;
use frontend\modules\forms\SubscribeForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

/* @var $tariffs Tariff */
/* @var $subForm SubscribeForm */

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

            <div class="col-12 col-lg-12">

                <div class="content-wrapper">

                    <div id="desc" class="detail-header mb-30">
                        <h1 class="h3 lh-h1 mt-5" itemprop="name"
                            style="max-height: 130px;overflow: hidden;"><?= $this->title ?></h1>
                    </div>

                    <div class="mt-50"></div>
                    <div>

                        <h4 class="heading-title">Тарифы</h4>

                        <?php if ($paymentStatus['msg'] !== null) : ?>
                            <?php if ($paymentStatus['status']) : ?>
                                <div class="col-md-12 alert alert-success"><?= $paymentStatus['msg'] ?></div>
                            <?php else : ?>
                                <div class="col-md-12 alert alert-danger"><?= $paymentStatus['msg'] ?></div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php foreach ($tariffs as $tariff): ?>
                            <div class="row">
                                <h4><?= $tariff->name ?></h4>
                                <div class="col-md-12 mb-10">
                                    <?= $tariff->description ?>
                                </div>
                                <?php foreach ($tariff->getPeriods() as $period): ?>

                                    <div class="col-md-4">
                                        <?php $form = ActiveForm::begin(['action' => Url::to(['/tariff']), 'method' => 'post']); ?>

                                        <figure class="tour-grid-item-01 box-shadow borr-10">

                                            <figcaption class="content">
                                                <div class="lot__block__info__content__offer"></div>
                                                <ul class="item-meta mt-10 pl-0">
                                                    <li class="pl-0">
                                                        <span class="font500"><h4> <?= $period['term'] ?> дней</h4></span>
                                                    </li>
                                                    <li class="pl-0">
                                                        <span class="font500"><h4> <?= $period['fee'] ?> ₽</h4></span>
                                                    </li>
                                                </ul>
                                                <?php if (!Yii::$app->user->isGuest) : ?>
                                                    <?php if (Yii::$app->accessManager->isSubscriber(Yii::$app->user->getId())) : ?>
                                                        <p>Подписка активна</p>
                                                    <?php else: ?>
                                                        <?= $form->field($subForm, 'tariffId')->hiddenInput(['value' => $tariff->id])->label(false); ?>
                                                        <?= $form->field($subForm, 'userId')->hiddenInput(['value' => Yii::$app->user->identity->getId()])->label(false); ?>
                                                        <?= $form->field($subForm, 'fee')->hiddenInput(['value' => $period['fee']])->label(false); ?>
                                                        <?= $form->field($subForm, 'term')->hiddenInput(['value' => $period['term']])->label(false); ?>

                                                        <small class="text-green font600">
                                                            <?= Html::submitButton('Приобрести за ' . $period['fee'] . ' руб.', ['class' => 'btn btn-primary btn-block text-white borr-10']) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <p>Что бы купить подписку - <a href="#loginFormTabInModal-login"
                                                                                   data-toggle="modal"
                                                                                   data-target="#loginFormTabInModal"
                                                                                   data-backdrop="static"
                                                                                   data-keyboard="false">Войдите
                                                        </a> или
                                                        <a href="#loginFormTabInModal-register" data-toggle="modal"
                                                           data-target="#loginFormTabInModal"
                                                           data-backdrop="static" data-keyboard="false">
                                                            Зарегистрируйтесь
                                                        </a></p>
                                                <?php endif; ?>
                                            </figcaption>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
