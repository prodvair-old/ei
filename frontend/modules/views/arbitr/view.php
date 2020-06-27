<?php

use common\models\db\Manager;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/** @var $model Manager */

$this->title = $model->getFullName();
$this->params['breadcrumbs'] = Yii::$app->params['breadcrumbs'];
?>

    <section class="page-wrapper page-detail">

        <div class="page-title bg-light d-none d-sm-block">

            <div class="container">

                <div class="row gap-15 align-items-center">

                    <div class="col-12">

                        <nav aria-label="breadcrumb">
                            <?= Breadcrumbs::widget([
                                'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                                'encodeLabels' => false,
                                'tag' => 'ol',
                                'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                                'homeLink' => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ]) ?>
                        </nav>

                    </div>

                </div>

            </div>

        </div>

        <div class="fullwidth-horizon- none--hide">

            <div class="fullwidth-horizon--inner">

                <div class="container">

                    <div class="fullwidth-horizon--item clearfix">

                        <ul id="horizon--nav" class="horizon--nav clearfix">
                            <li>
                                <a href="#info">Информация</a>
                            </li>
                            <li>
                                <a href="#stat">Статистика</a>
                            </li>
                            <?=($lots_bankrupt[0] != null)? '<li><a href="#other-lot">Другие лоты</a></li>': ''?>

                        </ul>

                    </div>

                </div>
            </div>
        </div>

        <div class="container pt-30">

            <div class="row gap-20 gap-lg-40">

                <div class="col-12">

                    <div class="content-wrapper">

                        <div id="desc" class="detail-header mb-30">
                            <span class="text-muted">Арбитражный управляющий</span>
                            <h1 class="h3 mt-2"><?= $this->title ?></h1>
                            <hr>
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-20">
                                <div class="mr-15 font-lg">
                                    <?=$model->id?>
                                </div>
                                <div class="mr-15 text-muted">|</div>
                                <div class="mr-15 rating-item rating-inline">
                                    <p class="rating-text font400 text-muted font-12 letter-spacing-1"><?=$model->organization->reg_number?> </p>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-7">
                                <div id="info" class="fullwidth-horizon--section">

                                    <h4 class="heading-title">Информация</h4>

                                    <ul class="list-icon-absolute what-included-list mb-30">

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">Рег.номер  </span><?=$model->sro->reg_number?></h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">ИНН </span><?=$model->profile->inn?></h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">ОГРН </span><?=$model->sro->ogrn?></h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">Адрес </span><?=$model->address?></h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">СРО </span> <a href="<?=Url::to(['sro/list'])?>/<?=$model->sro->id?>" target="_blank"><?=$model->sro->title?></a></h6>
                                        </li>

                                    </ul>

                                    <div class="mb-50"></div>

                                </div>
                            </div>

                            <div class="col-md-5">
                                <div id="stat" class="fullwidth-horizon--section">

                                    <h4 class="heading-title">Статистика</h4>

                                    <ul class="list-icon-absolute what-included-list mb-30">

<!--                                        <li>-->
<!--                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>-->
<!--                                            <h6><span class="font400">Дел в управлении </span>--><?//=$countCases?><!--</h6>-->
<!--                                        </li>-->

<!--                                        --><?php //echo "<pre>";
//                                        var_dump($model->torg->getLots()->all());
//                                        echo "</pre>";?>
<!--                                        <li>-->
<!--                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>-->
<!--                                            <h6><span class="font400">Количество опубликованных лотов </span>--><?//=count($model->torg->getLots()->all())?><!--</h6>-->
<!--                                        </li>-->

                                    </ul>

                                    <div class="mb-50"></div>

                                </div>
                            </div>
                        </div>


<!--                        --><?// if ($model->torg->lots != null) { ?>
<!---->
<!--                            <div id="other-lot" class="fullwidth-horizon--section">-->
<!---->
<!--                                <h4 class="heading-title">Лоты <span class="font400">Арбитражного управляющего</span></h4>-->
<!---->
<!--                                <div class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-30 mb-25">-->
<!---->
<!--                                    --><?//foreach ($model->torg->getLots()->all() as $lot_bankrupt) { echo LotBlock::widget(['lot' => $lot_bankrupt]); }?>
<!---->
<!--                                </div>-->
<!---->
<!--                                <div class="mb-50"></div>-->
<!---->
<!--                            </div>-->
<!--                        --><?// } ?>

                    </div>

                </div>

            </div>

        </div>

    </section>

<?php
$this->registerJsFile( 'js/custom-multiply-.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-multiply-' );
$this->registerJsFile( 'js/custom-core.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-core' );
?>