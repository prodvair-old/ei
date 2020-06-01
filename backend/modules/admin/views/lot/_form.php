<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $place common\models\db\Place */

use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='lot-common col-lg-8'>
            <div class='box box-primary'>
                <div class='box-header'>
                    <h3 class='box-title'><?= Yii::t('app', 'Common') ?></h3>
                </div>
                <div class='box-body'>

                    <?= $this->render('_form_common', [
                        'form'  => $form,
                        'model' => $model,
                    ]) ?>
                
                </div>
            </div>
            <div class='box box-primary'>
                <div class='box-header'>
                    <h3 class='box-title'><?= Yii::t('app', 'Address') ?></h3>
                </div>
                <div class='box-body'>

                    <?= $this->render('/place/_form', [
                        'form'  => $form,
                        'model' => $place,
                    ]) ?>
                
                </div>
            </div>
        </div>
        <div class='lot-status col-lg-4'>
            <div class='box box-primary'>
                <div class='box-header'>
                    <h3 class='box-title'><?= Yii::t('app', 'State') ?></h3>
                </div>
                <div class='box-body'>

                    <?= $this->render('_form_state', [
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
            <div class='box box-primary'>
                <div class='box-header'>
                    <h3 class='box-title'><?= Yii::t('app', 'Document') ?></h3>
                </div>
                <div class='box-body'>

                    <?= $this->render('_document', [
                        'model' => $model,
                    ]) ?>
                
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>
