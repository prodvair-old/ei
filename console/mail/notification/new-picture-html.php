<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Query\Lot\Lots */
?>
<?php if (isset($model->images) && count($model->images) > 0): ?>
    <p>К лоту добавлены фотографии.
        <div class='gallery'>
            <?php foreach($model->images as $image): ?>
                <img src="<?= $message->embed($image['min']) ?>" />
            <?php endforeach; ?>
        </div>
    </p>
<?php endif; ?>
