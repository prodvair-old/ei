<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $message yii\swiftmailer\Message */
/* @var $model common\models\Query\Lot\Lots */
?>
<?php if (isset($model->images) && count($model->images) > 0): ?>
    <p>К лоту добавлены фотографии.
        <div class='row'>
            <?php foreach($model->images as $image): ?>
                <div class='col-sm-4'>
                    <img class='img-responsive' src="<?= $message->embed(Yii::$app->params['frontLink'] . '/' . $image['min']) ?>" />
                </div>
            <?php endforeach; ?>
        </div>
    </p>
<?php endif; ?>
