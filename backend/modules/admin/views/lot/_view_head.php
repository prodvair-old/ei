<?php
/* @var $this yii\web\View */
/* @var $model common\models\Post */

use yii\helpers\Url;
use sergmoro1\blog\Module;
use common\models\Post;
?>

<h2 class='post-title'>
    <?= $model->getTitleLink() ?>
</h2>
<h3 class='post-subtitle'>
    <?= $model->subtitle ?>
</h3>

<div class='post-meta'>
    <span class='glyphicon glyphicon-calendar'></span> <?= $model->fullDate('created_at'); ?>
    <?php if($thumb = $model->getImage('thumb')): ?>
        <a href="<?= Url::to(['post/update', 'id' => $model->id]) ?>">
            <img src="<?= $thumb ?>" alt="<?= $model->getFileDescription(); ?>">
        </a>
    <?php endif; ?>
</div>
