<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use insolita\wgadminlte\CollapseBox;
use insolita\wgadminlte\LteConst;
use kartik\select2\Select2;

use backend\models\UserAccess;

/* @var $this yii\web\View */
/* @var $model backend\models\Editors\LotEditor */
/* @var $form ActiveForm */

$this->params['h1'] = 'Редактирование лота №'.$modelLot->id;
$this->title = 'Редактирование лота - '.$modelLot->title;

?>
<?php CollapseBox::begin([
             'type'=>LteConst::TYPE_DEFAULT,
             'isSolid'=>true,
             'tooltip'=>'Документы лота',
             'title'=>'Документы лота - '.count($lot->documents),
        ])?>
        <div class="row">
            <? foreach ($lot->documents as $document) { 
                switch ($document->format) {
                    case 'doc':
                        $icon = '<i class="fa fa-file-word-o"></i>';
                        break;
                    case 'docs':
                        $icon = '<i class="fa fa-file-word-o"></i>';
                        break;
                    case 'xls':
                        $icon = '<i class="fa fa-file-excel-o"></i>';
                        break;
                    case 'xlsx':
                        $icon = '<i class="fa fa-file-excel-o"></i>';
                        break;
                    case 'pdf':
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                        break;
                    case 'zip':
                        $icon = '<i class="fa fa-file-archive-o"></i>';
                        break;
                    default:
                        $icon = '<i class="fa fa-file-o"></i>';
                        break;
                }    
            ?>
                <div class="col-lg-12">
                    <a href="<?=$document->url?>" class="btn" target="_blank" download><?=$icon?> <?=$document->name?></a>
                </div>
            <? } ?>
        </div>
<?php CollapseBox::end()?>

<?php CollapseBox::begin([
             'type'=>LteConst::TYPE_DEFAULT,
             'isSolid'=>true,
             'tooltip'=>'Документы торга',
             'title'=>'Документы торга - '.count($lot->torg->documents),
        ])?>
        <div class="row">
            <? foreach ($lot->torg->documents as $document) { 
                switch ($document->format) {
                    case 'doc':
                        $icon = '<i class="fa fa-file-word-o"></i>';
                        break;
                    case 'docs':
                        $icon = '<i class="fa fa-file-word-o"></i>';
                        break;
                    case 'xls':
                        $icon = '<i class="fa fa-file-excel-o"></i>';
                        break;
                    case 'xlsx':
                        $icon = '<i class="fa fa-file-excel-o"></i>';
                        break;
                    case 'pdf':
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                        break;
                    case 'zip':
                        $icon = '<i class="fa fa-file-archive-o"></i>';
                        break;
                    default:
                        $icon = '<i class="fa fa-file-o"></i>';
                        break;
                }    
            ?>
                <div class="col-lg-12">
                    <a href="<?=$document->url?>" class="btn" target="_blank" download><?=$icon?> <?=$document->name?></a>
                </div>
            <? } ?>
        </div>
<?php CollapseBox::end()?>

<?php CollapseBox::begin([
             'type'=>LteConst::TYPE_DEFAULT,
             'isSolid'=>true,
             'tooltip'=>'Документы дел по лоту',
             'title'=>'Документы дел по лоту - '.count($lot->torg->case->documents),
        ])?>
        <div class="row">
            <? foreach ($lot->torg->case->documents as $document) { 
                switch ($document->format) {
                    case 'doc':
                        $icon = '<i class="fa fa-file-word-o"></i>';
                        break;
                    case 'docs':
                        $icon = '<i class="fa fa-file-word-o"></i>';
                        break;
                    case 'xls':
                        $icon = '<i class="fa fa-file-excel-o"></i>';
                        break;
                    case 'xlsx':
                        $icon = '<i class="fa fa-file-excel-o"></i>';
                        break;
                    case 'pdf':
                        $icon = '<i class="fa fa-file-pdf-o"></i>';
                        break;
                    case 'zip':
                        $icon = '<i class="fa fa-file-archive-o"></i>';
                        break;
                    default:
                        $icon = '<i class="fa fa-file-o"></i>';
                        break;
                }    
            ?>
                <div class="col-lg-12">
                    <a href="<?=$document->url?>" class="btn" target="_blank" download><?=$icon?> <?=$document->name?></a>
                </div>
            <? } ?>
        </div>
<?php CollapseBox::end()?>

<?php $form = ActiveForm::begin(); ?>
    <?php CollapseBox::begin([
             'type'=>LteConst::TYPE_SUCCESS,
             'collapseRemember' => true,
             'collapseDefault' => false,
             'isSolid'=>true,
             'boxTools'=>Html::a('Назад', Url::to(['lots/index']),['class' => 'btn']).' '.Html::a('Удалить', '/lots/delete?id='.$modelLot->id,['class' => 'btn btn-danger', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите Удалить этот лот?', 'data-method' => 'post']).' '.Html::a((($modelLot->published)? 'Снять с публикации' : 'Опубликовать'), '/lots/published?id='.$modelLot->id,['class' => 'btn btn-primary', 'title' => (($modelLot->published)? 'Снять с публикации' : 'Опубликовать'), 'aria-label' => (($modelLot->published)? 'Снять с публикации' : 'Опубликовать'), 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите '.(($modelLot->published)? 'Снять с публикации' : 'Опубликовать').' этот лот?', 'data-method' => 'post']).' '.Html::a('Страница лота', Yii::$app->params['frontLink'].'/'.$lot->url,['class' => 'btn', 'target' => '_blank']),
             'tooltip'=>'Данные лота',
             'title'=>'Главные характеристики лота',
        ])?>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <div class="row">
            <? foreach ($lot->images as $id => $image) { ?>
                <div class="col-lg-2">
                    <?=Html::a('<img src="'.Yii::$app->params['frontLink'].'/'.$image['min'].'" style="max-width: 100%" alt="">', Url::to(['lots/image-del', 'id' => $id, 'lotId' => $lot->id]),['class' => 'btn', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 1, 'data-confirm' => 'Вы уверены, что хотите Удалить Картинку №'.$id.' у этого лота?', 'data-method' => 'post'])?>
                </div>
            <? } ?>
            <div class="col-lg-12">
                <br>
                <?= $form->field($modelLot, 'uploads[]')->fileInput(['multiple' => true, 'accept' => 'image/jpeg,image/png,image/jpg']) ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($modelLot, 'msgId') ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($modelLot, 'status') ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($modelLot, 'lotNumber') ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12"><?= $form->field($modelLot, 'title') ?></div>
            <div class="col-lg-12"><?= $form->field($modelLot, 'description')->textarea() ?></div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-3"><?= $form->field($modelLot, 'startPrice') ?></div>
        </div>
        <div class="row">
            <div class="col-lg-3"><?= $form->field($modelLot, 'step') ?></div>
            <div class="col-lg-3"><?= $form->field($modelLot, 'stepTypeId')->dropDownList([
                    1 => 'Процент',
                    2 => 'Сумма',
                ]) ?></div>
            <div class="col-lg-3"><?= $form->field($modelLot, 'deposit') ?></div>
            <div class="col-lg-3"><?= $form->field($modelLot, 'depositTypeId')->dropDownList([
                    1 => 'Процент',
                    2 => 'Сумма',
                ]) ?></div>
        </div>

        <!-- <?= $form->field($modelLot, 'bankId') ?> -->

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php CollapseBox::end()?>
<?php ActiveForm::end(); ?>
<? if ($modelTorg != null) { ?>
<hr>
<?php $form = ActiveForm::begin(); ?>
    <?php CollapseBox::begin([
             'type'=>LteConst::TYPE_DEFAULT,
             'isSolid'=>true,
             'boxTools'=>Html::a('Назад', Url::to(['lots/index']),['class' => 'btn']),
             'tooltip'=>'Данные торга',
             'title'=>'Информация по торгу',
        ])?>

        <div class="form-group">
            <?= (UserAccess::forManager('torgs','edit'))? Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) : 'У вас нет прав на редактирование' ?>
        </div>

        <?= $form->field($modelTorg, 'publisherId') ?>
        <?= $form->field($modelTorg, 'ownerId') ?>
        <?= $form->field($modelTorg, 'etpId') ?>
        <?= $form->field($modelTorg, 'typeId') ?>
        <?= $form->field($modelTorg, 'tradeTypeId') ?>
        <?= $form->field($modelTorg, 'bankruptId') ?>
        <?= $form->field($modelTorg, 'caseId') ?>
        <?= $form->field($modelTorg, 'oldId') ?>
        <?= $form->field($modelTorg, 'msgId') ?>
        <?= $form->field($modelTorg, 'type') ?>
        <?= $form->field($modelTorg, 'tradeType') ?>
        <?= $form->field($modelTorg, 'description') ?>
        <?= $form->field($modelTorg, 'createdAt') ?>
        <?= $form->field($modelTorg, 'updatedAt') ?>
        <?= $form->field($modelTorg, 'startDate') ?>
        <?= $form->field($modelTorg, 'endDate') ?>
        <?= $form->field($modelTorg, 'completeDate') ?>
        <?= $form->field($modelTorg, 'publishedDate') ?>
        <?= $form->field($modelTorg, 'info') ?>
    
        <div class="form-group">
            <?= (UserAccess::forManager('torgs','edit'))? Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) : 'У вас нет прав на редактирование' ?>
        </div>

    <?php CollapseBox::end()?>
<?php ActiveForm::end(); ?>
<? } ?>