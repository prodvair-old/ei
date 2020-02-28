<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use insolita\wgadminlte\CollapseBox;
use insolita\wgadminlte\LteConst;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use kartik\datetime\DateTimePicker;

use backend\models\UserAccess;
use backend\models\AddField;

use common\models\Query\LotsCategory;

/* @var $this yii\web\View */
/* @var $model backend\models\Editors\LotEditor */
/* @var $form ActiveForm */
$categorys = LotsCategory::find()->orderBy('id ASC')->all();

$this->params['h1'] = 'Редактирование лота №'.$modelLot->id;
$this->title = 'Редактирование лота - '.$modelLot->title;

?>
<?php $form = ActiveForm::begin(['id' => 'add-lot-field']); ?>
    <?php Modal::begin([
        'header' => 'Новое поле для лота',
        'options' => ['id' => 'modal-lot'],
        'footer' => Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'data' => ['toggle' => 'modal', 'target' => '#modal-lot']])
    ]); ?>
        <?= $form->field(new AddField(), 'name')->textInput(['id' => 'add-lot-field-input']) ?>
    <?php Modal::end(); ?>
<?php ActiveForm::end(); ?>

<?php $form = ActiveForm::begin(['id' => 'add-torg-field']); ?>
    <?php Modal::begin([
        'header' => 'Новое поле для торга',
        'options' => ['id' => 'modal-torg'],
        'footer' => Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'data' => ['toggle' => 'modal', 'target' => '#modal-torg']])
    ]); ?>
        <?= $form->field(new AddField(), 'name')->textInput(['id' => 'add-torg-field-input']) ?>
    <?php Modal::end(); ?>
<?php ActiveForm::end(); ?>

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

<?php CollapseBox::begin([
            'type'=>LteConst::TYPE_SUCCESS,
            'collapseRemember' => true,
            'collapseDefault' => false,
            'isSolid'=>true,
            'boxTools'=>Html::a('Назад', Url::to(['lots/index']),['class' => 'btn']).' '.Html::a('Удалить', '/lots/delete?id='.$modelLot->id,['class' => 'btn btn-danger', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите Удалить этот лот?', 'data-method' => 'post']).' '.Html::a((($modelLot->published)? 'Снять с публикации' : 'Опубликовать'), '/lots/published?id='.$modelLot->id,['class' => 'btn btn-primary', 'title' => (($modelLot->published)? 'Снять с публикации' : 'Опубликовать'), 'aria-label' => (($modelLot->published)? 'Снять с публикации' : 'Опубликовать'), 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите '.(($modelLot->published)? 'Снять с публикации' : 'Опубликовать').' этот лот?', 'data-method' => 'post']).' '.Html::a('Страница лота', Yii::$app->params['frontLink'].'/'.$lot->url,['class' => 'btn', 'target' => '_blank']),
            'tooltip'=>'Данные лота',
            'title'=>'Главные характеристики лота',
    ])?>
    <?php $form = ActiveForm::begin(); ?>
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
            <div class="col-lg-4">
                <?=$form->field($modelLot, 'categorys')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map($categorys, 'id', 'name'),
                        'options' => ['placeholder' => 'Выберите категорию ...', 'id' => 'category-select'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);?>
            </div>
            <div class="col-lg-8">
                <?=$form->field($modelLot, 'subCategorys')->widget(Select2::classname(), [
                        'data' => $subCategorysItems,
                        'options' => ['multiple'=>true, 'placeholder' => 'Найти менеджера'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Ошибка поиска результатов ...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['lots/category-list']),
                                'dataType' => 'json',
                                'data' => new JsExpression('
                                    function(params) { 
                                        return {q:params.term, type: $(\'#type-select\').val(), category: $(\'#category-select\').val()}; 
                                    }
                                ')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(data) { return data.text; }'),
                            'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                        ],
                    ]);?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-3"><?= $form->field($modelLot, 'startPrice') ?></div>
            <div class="col-lg-4">
                
            </div>
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

        <hr>

        <div class="my-field"></div>

        <div class="row">
            <? foreach ($modelLot->info as $key => $info): ?>
                <?if (is_array($info)) { ?>
                    <div class="col-lg-12">
                        <hr>

                        <h4><?= $key ?></h4>

                        <div class="row">
                            <? foreach ($info as $name => $value): ?>
                                <div class="col-lg-6">
                                    <?= $form->field($modelLot, "info[$key][$name]")->textInput(['value'=>$value])->label($name) ?>
                                </div>
                            <? endforeach ?>
                        </div>

                        <hr>
                    </div>

                <? } else { ?>
                    <div class="col-lg-12">
                        <?= $form->field($modelLot, "info[$key]")->textInput(['value'=>$info])->label($key) ?>
                    </div>
                <? } ?>
            <? endforeach ?>
        </div>

        <div id="ajax-content-lot" class="row"></div>


        <!-- <?= $form->field($modelLot, 'bankId') ?> -->

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Добавить поле', 'javascript:void(0);', ['class' => 'btn btn-success', 'data' => ['toggle' => 'modal', 'target' => '#modal-lot']]) ?>
        </div>

    <?php ActiveForm::end(); ?>
<?php CollapseBox::end()?>

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
            <?= (UserAccess::forManager('torgs','edit'))? Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) : 'У вас нет прав на редактирование' ?>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($modelTorg, 'msgId') ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($modelTorg, 'typeId')->dropDownList([
                        1 => 'Банкротное имущество',
                        2 => 'Арестованное имущество',
                        3 => 'Залоговое имущество',
                ], ['id' => 'type-select']) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($modelTorg, 'tradeTypeId')->dropDownList([
                        1 => 'Публичное предложение',
                        2 => 'Открытый аукцион',
                    ]) ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-6">
                <?=$form->field($modelTorg, 'publisherId')->widget(Select2::classname(), [
                        'options' => ['placeholder' => 'Найти менеджера'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Ошибка поиск результатов ...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['managers/list']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(data) { return data.text; }'),
                            'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                        ],
                    ]);?>
            </div>
            <div class="col-lg-6">
                <?=$form->field($modelTorg, 'ownerId')->widget(Select2::classname(), [
                        'options' => ['placeholder' => 'Найти владельца торга'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Ошибка поиск результатов ...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['owners/list']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(data) { return data.text; }'),
                            'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                        ],
                    ]);?>
            </div>
            <div class="col-lg-6">
                <?=$form->field($modelTorg, 'etpId')->widget(Select2::classname(), [
                        'options' => ['placeholder' => 'Найти торговую площадку'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Ошибка поиск результатов ...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['etp/list']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(data) { return data.text; }'),
                            'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                        ],
                    ]);?>
            </div>
            <div class="col-lg-6">
                <?=$form->field($modelTorg, 'bankruptId')->widget(Select2::classname(), [
                        'options' => ['placeholder' => 'Найти должника'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Ошибка поиск результатов ...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['bankrupts/list']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(data) { return data.text; }'),
                            'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                        ],
                    ]);?>
            </div>
            <div class="col-lg-6">
                <?=$form->field($modelTorg, 'caseId')->widget(Select2::classname(), [
                        'options' => ['placeholder' => 'Найти дело по торгу'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Ошибка поиск результатов ...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['cases/list']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(data) { return data.text; }'),
                            'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                        ],
                    ]);?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-6"><?= $form->field($modelTorg, 'startDate')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Выберите дату и время'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii:ss'
                    ]
                ]) ?></div>
            <div class="col-lg-6"><?= $form->field($modelTorg, 'endDate')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Выберите дату и время'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii:ss'
                    ]
                ]) ?></div>
            <div class="col-lg-6"><?= $form->field($modelTorg, 'completeDate')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Выберите дату и время'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii:ss'
                    ]
                ]) ?></div>
            <div class="col-lg-6"><?= $form->field($modelTorg, 'publishedDate')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Выберите дату и время'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii:ss'
                    ]
                ]) ?></div>
        </div>

        <div class="row">
            <div class="col-lg-12"><?= $form->field($modelTorg, 'description')->textarea() ?></div>
        </div>
        
        <hr>
        <div class="row">
            <? foreach ($modelTorg->info as $key => $info): ?>
                <?if (is_array($info)) { ?>
                    <div class="col-lg-12">
                        <hr>

                        <h4><?= $key ?></h4>

                        <div class="row">
                            <? foreach ($info as $name => $value): ?>
                                <div class="col-lg-6">
                                    <?= $form->field($modelTorg, "info[$key][$name]")->textInput(['value'=>$value])->label($name) ?>
                                </div>
                            <? endforeach ?>
                        </div>

                        <hr>
                    </div>

                <? } else { ?>
                    <div class="col-lg-12">
                        <?= $form->field($modelTorg, "info[$key]")->textInput(['value'=>$info])->label($key) ?>
                    </div>
                <? } ?>
            <? endforeach ?>
        </div>

        <div id="ajax-content-torg" class="row"></div>
    
        <div class="form-group">
            <?= (UserAccess::forManager('torgs','add'))? Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) : 'У вас нет прав на редактирование' ?>
            <?= (UserAccess::forManager('torgs','add'))? Html::a('Добавить поле', 'javascript:void(0);', ['class' => 'btn btn-success', 'data' => ['toggle' => 'modal', 'target' => '#modal-torg']]) : '' ?>
        </div>

    <?php CollapseBox::end()?>
<?php ActiveForm::end(); ?>
<? } ?>

<?php
$script = <<< JS
jQuery('#add-lot-field').on('submit',function(e){
    e.preventDefault();

    jQuery.post('/add-field-lot?name=' + jQuery('#add-lot-field-input').val(), function(data){
        jQuery('#ajax-content-lot').append(data);
    });

    return false;
});
jQuery('#add-torg-field').on('submit',function(e){
    e.preventDefault();

    jQuery.post('/add-field-torg?name=' + jQuery('#add-torg-field-input').val(), function(data){
        jQuery('#ajax-content-torg').append(data);
    });

    return false;
});
JS;
$this->registerJs($script, View::POS_END);
?>