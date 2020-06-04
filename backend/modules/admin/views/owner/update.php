<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Owner */
/* @var $place common\models\db\Organization */
/* @var $place common\models\db\Place */

$this->title = Yii::t('app', 'Update') . ' ' . Yii::t('app', 'owner');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Owners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->organization->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'organization' => $organization,
    'place' => $place,
]) ?>
