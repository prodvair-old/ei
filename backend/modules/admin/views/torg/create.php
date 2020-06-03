<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Torg */
/* @var $pledge common\models\db\TorgPledge */

$this->title = Yii::t('app', 'Create') . ' ' . Yii::t('app', 'auction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'pledge' => $pledge,
]) ?>
