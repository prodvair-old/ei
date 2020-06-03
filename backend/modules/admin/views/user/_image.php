<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\User */
/* @var $form yii\widgets\ActiveForm */

use sergmoro1\uploader\widgets\Uploader;
?>

<?= Uploader::widget([
    'model'         => $model,
    'draggable'     => true,
    'appendixView'  => '/user/appendix.php',
    'secure'        => false,
]) ?>
