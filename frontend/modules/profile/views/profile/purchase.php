<?php

use common\models\db\Purchase;
use frontend\modules\components\PurchasedReportWidget;
use yii\widgets\Breadcrumbs;
use frontend\modules\profile\components\ProfileMenu;

/* @var $this yii\web\View */
/* @var $model Purchase[] */
/* @var $caption string profile part caption */

$name = Yii::$app->user->identity->getFullName();
$this->title = "Профиль – $name";
$this->params[ 'breadcrumbs' ][] = [
    'label'    => 'Профиль',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url'      => ['user/index']
];
$this->params[ 'breadcrumbs' ][] = [
    'label'    => $caption,
    'template' => '<li class="breadcrumb-item" aria-current="page">{link}</li>',
];
?>

<section class="page-wrapper page-detail">

    <div class="page-title border-bottom pt-25 mb-0 border-bottom-0">

        <div class="container">

            <div class="row gap-15 align-items-center">

                <div class="col-12 col-md-7">

                    <nav aria-label="breadcrumb">
                        <?= Breadcrumbs::widget([
                            'itemTemplate'       => '<li class="breadcrumb-item">{link}</li>',
                            'encodeLabels'       => false,
                            'tag'                => 'ol',
                            'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                            'homeLink'           => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
                            'links'              => isset($this->params[ 'breadcrumbs' ]) ? $this->params[ 'breadcrumbs' ] : [],
                        ]) ?>
                    </nav>

                </div>

            </div>

        </div>

    </div>

    <div class="container pt-30">

        <div class="row gap-20 gap-lg-40">

            <div class="col-12 col-lg-3">

                <aside class="-kit sidebar-wrapper">

                    <div class="bashboard-nav-box">

                        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Вы авторизованы как:</h3>
                        </div>
                        <?= ProfileMenu::widget(['page' => 'purchase']) ?>

                    </div>

                </aside>

            </div>

            <div class="col-12 col-lg-9">

                <div class="content-wrapper">

                    <div class="form-draft-payment">

                        <h3 class="heading-title"><span><?= $caption ?></span></h3>

                        <div class="clear"></div>

                        <div class="row gap-30">

                            <div class="col-12 col-md-12 col-lg-8">
                                <? if ($model) {
                                    try {
                                        echo PurchasedReportWidget::widget(['reports' => $model]);
                                    } catch (\Exception $e) {
                                        echo (YII_ENV_PROD) ? 'Ошибка загрузки отчетов' : $e->getMessage();
                                    }

                                } ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php
$this->registerJsFile('/js/cssworld.ru-xcal.js', $options = ['position' => yii\web\View::POS_HEAD], $key = 'date_picker');
$this->registerCssFile('/css/cssworld.ru-xcal.css');
?>
