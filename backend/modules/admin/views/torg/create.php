<?php
/* @var $this yii\web\View */
/* @var $model common\models\Post */

use sergmoro1\blog\Module;

$this->title = Module::t('core', 'Add');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
