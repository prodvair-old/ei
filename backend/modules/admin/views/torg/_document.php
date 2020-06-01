<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\widgets\Document;
?>

<?php if (
    ($lot = (isset($model->documents) && count($model->documents) > 0)) || 
    ($torg = (isset($model->torg->documents) && count($model->torg->documents) > 0)) || 
    ($case = (isset($model->torg->casefile->documents) && count($model->torg->casefile->documents) > 0))
): ?>

    <?= Document::widget([
        'title' => Yii::t('app', 'Lot'),
        'model' => $model,
    ]) ?>

    <?= Document::widget([
        'title' => Yii::t('app', 'Torg'),
        'model' => $model->torg,
    ]) ?>

    <?= Document::widget([
        'title' => Yii::t('app', 'Casefile'),
        'model' => $model->torg->casefile,
    ]) ?>

<?php else: ?>
    <?= Yii::t('app', 'No documents related to the Lot.') ?>
<?php endif; ?>
