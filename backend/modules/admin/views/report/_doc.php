<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use sergmoro1\uploader\widgets\Uploader;
?>

<?= Uploader::widget([
    'btns'            => ['choose' => ['label' => Yii::t('app', 'Document')]] ,
    'model'           => $model,
    'draggable'       => true,
    'appendixView'    => '/lot/appendix.php',
    'secure'          => false,
    'allowedTypes'    => ['application/pdf', 'application/zip', 'application/msword', 'application/excel'],
    'allowedTypesReg' => ['/application\\/[pdf|zip|msword|excel]/i'],
]) ?>
