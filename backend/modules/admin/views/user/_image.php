<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\User */

use sergmoro1\uploader\widgets\Uploader;

?>

<?= Uploader::widget([
    'model'         => $model,
    'draggable'     => true,
    'appendixView'  => '/user/appendix.php',
    'cropAllowed'   => true,
]) ?>
