<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Torg */
/* @var $pledge common\models\db\TorgPledge */

$this->title = Yii::t('app', 'Update') . ' ' . Yii::t('app', 'auction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->shortTitle, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'pledge' => $pledge,
]) ?>
