<?php
/* @var $this yii\web\View */
/* @var $model common\models\Post */

use yii\helpers\Html;
use yii\helpers\Url;
use sergmoro1\blog\Module;
?>

<div class='post-meta'>
    <p>
        <span class='glyphicon glyphicon-tag' aria-hidden='true' title="<?= Module::t('core', 'tags') ?>"></span>
        <?= implode(', ', $model->tagLinks); ?>
        <br>
        <span class='glyphicon glyphicon-comment' aria-hidden="true" title='<?= Module::t('core', 'comments') ?>'></span>
        <?= Html::a(Module::t('core', 'comments'), $model->url . '#comments') . " ({$model->commentCount})"; ?>
    </p>
</div>

