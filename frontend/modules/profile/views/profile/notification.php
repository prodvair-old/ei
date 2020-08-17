<?php
/* @var $this yii\web\View */
/* @var $formModel frontend\models\user\NotificationForm */
/* @var $caption string profile part caption */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use frontend\modules\profile\components\ProfileMenu;

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

                        <?= ProfileMenu::widget(['page' => 'notification']) ?>

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
                                <?php if( Yii::$app->session->hasFlash('success') ): ?>
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <?php echo Yii::$app->session->getFlash('success'); ?>
                                    </div>
                                <?php endif;?>
                                <?php $form = ActiveForm::begin() ?>
                                <div class="col-inner">
                                    <div class="row gap-20">

                                        <div class="col-sm-12">
                                            <?= $form->field($formModel, 'new_picture')->checkbox() ?>
                                            <?= $form->field($formModel, 'new_report')->checkbox() ?>
                                            <?= $form->field($formModel, 'price_reduction')->checkbox() ?>
                                        </div>

                                        <div class="mb-30"></div>

                                        <div class="row gap-10 mt-15 justify-content-center justify-content-md-start">
                                            <div class="col-auto">
                                                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success borr-10']) ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
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
