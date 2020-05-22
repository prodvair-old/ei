<?php
/* @var $this yii\web\View */
/* @var $model common\models\Post */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\Carousel;

use sergmoro1\blog\Module;

$this->title = Module::t('core', 'View');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->getTitle();

?>
<div class='post-view'>

    <div class='post-actions row'>
        <div class='col-sm-6'>
            <?= Html::a(Module::t('core', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            <?php if($model->fileCount): ?>

                <?php Modal::begin([
                    'header' => Module::t('core', 'Pictures connected with the post'),
                    'toggleButton' => ['label' => Module::t('core', 'Pictures'), 'tag' => 'a', 'class' => 'btn btn-default'],
                ]); ?>

                    <?= Carousel::widget([
                        'items' => $model->prepareSlider('div', [], ['class' => 'img-responsive'], true), 
                        'options' => ['data-interval' => ''],
                        'controls' => [
                            '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
                            '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>',
                        ]
                    ]); ?>

                <?php Modal::end(); ?>

            <?php endif; ?>
        </div>
        <div class='col-sm-6 text-right'>
            <?= Html::a(Module::t('core', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Module::t('core', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class='post-preview'>

        <?= $this->render('_view_head', [
            'model' => $model, 
        ]); ?>

        <div class='excerpt'>
            <?= $model->excerpt ?>
        </div>

        <?= $model->content ?>
        
        <?php if(mb_strlen(trim($model->resume), 'UTF-8') > 0 ): ?>
            <h3><?= Module::t('core', 'Resume'); ?></h3>
            <div class='resume'>
                <?= $model->resume ?>
            </div>
        <?php endif; ?>
        
        <?php echo $this->render('_view_foot', [
            'model' => $model, 
        ]); ?>
    </div>

</div>
