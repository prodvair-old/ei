<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use sergmoro1\uploader\widgets\Uploader;
?>

<?= Uploader::widget([
    'model'         => $model,
    'draggable'     => true,
    'appendixView'  => '/lot/appendix.php',
]) ?>
