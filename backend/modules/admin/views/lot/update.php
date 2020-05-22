<?php
/* @var $this yii\web\View */
/* @var $model common\models\Post */

use sergmoro1\blog\Module;

$this->title = Module::t('core', 'Update');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getTitle(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
