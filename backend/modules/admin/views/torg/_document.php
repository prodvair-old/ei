<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\widgets\Document;
?>

<?php if (
    ($torg = (isset($model->documents) && count($model->documents) > 0)) || 
    ($case = (isset($model->casefile->documents) && count($model->casefile->documents) > 0))
): ?>

    <?= Document::widget([
        'title' => Yii::t('app', 'Torg'),
        'model' => $model,
    ]) ?>

    <?= Document::widget([
        'title' => Yii::t('app', 'Casefile'),
        'model' => $model->casefile,
    ]) ?>

<?php else: ?>
    <?= Yii::t('app', 'No documents related to the Auction.') ?>
<?php endif; ?>
