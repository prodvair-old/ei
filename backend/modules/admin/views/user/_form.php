<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Torg */
/* @var $pladge common\models\db\TorgPledge */

use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='lot-common col-lg-8'>
            <div class='box box-primary'>
                <div class='box-header'>
                    <h3 class='box-title'><?= Yii::t('app', 'Profile') ?></h3>
                </div>
                <div class='box-body'>

                    <?= $this->render('/profile/_form', [
                        'form'  => $form,
                        'model' => $profile,
                    ]) ?>
                
                </div>
            </div>
            <div class='box box-primary'>
                <div class='box-header'>
                    <h3 class='box-title'><?= Yii::t('app', 'Notification') ?></h3>
                </div>
                <div class='box-body'>

                    <?= $this->render('_form_notification', [
                        'form'  => $form,
                        'model' => $notification,
                    ]) ?>
                
                </div>
            </div>
        </div>
        <div class='lot-status col-lg-4'>
            <div class='box box-primary'>
                <div class='box-header'>
                    <h3 class='box-title'><?= Yii::t('app', 'Rights') ?></h3>
                </div>
                <div class='box-body'>

                    <?= $this->render('_form_status', [
                        'form'  => $form,
                        'model' => $model,
                    ]) ?>
                
                </div>
            </div>
            <div class='box box-primary'>
                <div class='box-header'>
                    <h3 class='box-title'><?= Yii::t('app', 'Image') ?></h3>
                </div>
                <div class='box-body'>

                    <?= $this->render('_image', [
                        'model' => $model,
                    ]) ?>
                
                </div>
            </div>
       </div>
    </div>

<?php ActiveForm::end(); ?>
