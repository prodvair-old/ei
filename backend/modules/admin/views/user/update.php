<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\User */
/* @var $profile common\models\db\Profile */
/* @var $notification common\models\db\Notification */
/* @var $manager common\models\db\Manager */

$this->title = Yii::t('app', 'Update') . ' ' . Yii::t('app', 'user');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'profile' => $profile,
    'notification' => $notification,
    'manager' => $manager,
]) ?>
