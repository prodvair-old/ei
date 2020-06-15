<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use sergmoro1\uploader\widgets\Uploader;
?>

<?= Uploader::widget([
    'btns'          => ['choose' => ['label' => Yii::t('app', 'Image') .' & '. Yii::t('app', 'Document')]] ,
    'model'         => $model,
    'draggable'     => true,
    'appendixView'  => '/lot/appendix.php',
    'allowedTypes'    => ['image/jpg', 'image/jpeg', 'image/png', 'application/pdf', 'application/zip', 'application/msword', 'application/excel'],
    'allowedTypesReg' => '/[image|application]\\/[jpg|jpeg|png|pdf|zip|msword|excel]/i',
]) ?>
