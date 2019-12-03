<?php
use frontend\components\NumberWords;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use common\models\Query\LotsCategory;

$priceClass = 'text-secondary';
try {
    if ($lot->torgy_tradetype == 'PublicOffer') {
        $priceClass = 'text-primary';
    } else if ($lot->torgy_tradetype == 'OpenedAuction') {
        $priceClass = 'text-primary';
    }
} catch (\Throwable $th) {
    $priceClass = 'text-primary';
}

$lotsSubcategory[0] = 'Все подкатегории';
$lotsCategory = LotsCategory::find()->where(['or', ['not', ['zalog_categorys' => null]], ['translit_name' => 'lot-list']])->orderBy('id ASC')->all();

if ($lot->categorys[0] != null) {
    foreach ($lotsCategory as $keyCategory => $value) {
        foreach ($lot->categorys as $category) {
            if ($value->zalog_categorys[$category->categoryId]['name'] !== null) {
                $lotCategorySelect[$keyCategory] = ['selected' => true];
                $lotSubCategorySelect[$category->categoryId] = ['selected' => true];
                $lotCategoryId = $keyCategory;
            }
        }
    }
}




if (!$lotSubCategorySelect) {
    $lotSubCategoryDisable = true;
} else {
    foreach ($lotsCategory[$lotCategoryId]->zalog_categorys as $key => $value) {
        $lotsSubcategory[$key] = $value['name'];
    }
    $lotSubCategoryDisable = false;
}
?>
                
    <figure class="tour-<?=$type?>-item-01" id="zalog-<?=$lot->id?>">

            <div class="d-flex flex-column flex-sm-row align-items-xl-center">
        
                <div>
                <?php $form = ActiveForm::begin(['action'=>Url::to(['user/lot-images']), 'options' => ['enctype' => 'multipart/form-data', 'id'=>'lot-'.$lot->id.'-zalog-upload-images']]) ?>

                    <div class="avatar-upload">
                        <div class="image image-galery lot-<?=$lot->id?>-upload-image-tag">
                            <img class="profile-pic d-block" src="<?=($lot->lotImage)? $lot->lotImage[0] : 'img/img.svg'?>" alt="" />
                            <?= ($lot->lotImage[1])? '<img src="'.$lot->lotImage[1].'" alt="" />'  : ''?>
                            <?= ($lot->lotImage[2])? '<img src="'.$lot->lotImage[2].'" alt="" />'  : ''?>
                            <?= ($lot->lotImage[3])? '<img src="'.$lot->lotImage[3].'" alt="" />'  : ''?>
                            <?= ($lot->lotImage[4])? '<img src="'.$lot->lotImage[4].'" alt="" />'  : ''?>
                            <div class="image-galery__control"></div>
                        </div>
                        <label for="images-<?=$lot->id?>-upload">
                            <div class="upload-button text-secondary line-1">
                                <div>
                                    <i class="fas fa-upload text-primary"></i>
                                    <span class="d-block font12 text-uppercase font700 mt-10 text-primary">Максимальный размер:<br/>250 Мб</span>
                                </div>
                            </div>
                        </label>
                        <?= $form->field($model, 'lotId')->hiddenInput(['class' => 'form-control', 'value' => $lot->id])->label(false) ?>
                        <?= $form->field($model, 'images[]')->fileInput(['class'=>'file-upload', 'onChange' => "uploadLotImage(".$lot->id.")", 'data-lotid' => $lot->id, 'id'=>'images-'.$lot->id.'-upload', 'multiple' => true, 'accept' => 'image/png,image/jpeg'])->label(false) ?>
                        <div class="labeling">
                            <i class="fas fa-upload"></i> <span class="lot-<?=$lot->id?>-zalog-image-info">Загрузить фото</span>
                        </div>
                    </div>

                    <?php ActiveForm::end() ?>
                </div>

                <div>
                    <figcaption class="content">
                        <a href="<?= ($lot->lotUrl)? $lot->lotUrl : 'javascript:void(0);' ?>" target="_blank">
                            <h3 class="lot-block__title <?=(!empty($lot->lot_archive))? ($lot->lot_archive)? 'text-muted' : '' : ''?>"><?= $lot->lotTitle?> <?=(!empty($lot->lot_archive))? ($lot->lot_archive)? '<span class="text-primary">(Архив)</span>' : '' : ''?></h3>
                        </a>

                        <hr>
                        <ul class="item-meta lot-block__info">
                            <li><?= Yii::$app->formatter->asDate($lot->lot_timepublication, 'long')?></li>
                            <li>	
                                <div class="rating-item rating-sm rating-inline clearfix">
                                    <p class="rating-text font600 text-muted font-12 letter-spacing-1"><?=NumberWords::widget(['number' => $lot->lotViews, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?></p>
                                </div>
                            </li>
                            <li>
                                <div <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" class="wish-star" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star" data-id="'.$lot->id.'" data-type="'.$lot->lotType.'"'?>>
                                    <img src="img/star<?=($lot->lotWishId)? '' : '-o' ?>.svg" alt="">
                                </div>
                            </li>
                        </ul>
                        <hr>
                        <?php $form = ActiveForm::begin(['action'=>Url::to(['user/lot-category']), 'options' => ['enctype' => 'multipart/form-data', 'id'=>'lot-'.$lot->id.'-zalog-categorys']]) ?>
                            <?= $form->field($modelCategory, 'lotId')->hiddenInput(['class' => 'form-control', 'value' => $lot->id])->label(false) ?>
                            <ul class="item-meta lot-block__info">
                                <li>
                                    <?=$form->field($modelCategory, 'categorys')->dropDownList(
                                                    ArrayHelper::map($lotsCategory, 'id', 'name'),
                                                [
                                                    'class'=>'chosen-zalog-category-select form-control form-control-sm', 
                                                    'data-placeholder'=>'Все категории', 
                                                    'data-lotid'=>$lot->id, 
                                                    'tabindex'=>'2',
                                                    'options' => $lotCategorySelect
                                                ])
                                                ->label('Категория');?>
                                </li>
                                <li>
                                    <?=$form->field($modelCategory, 'subCategorys')->dropDownList(
                                                    $lotsSubcategory,
                                                [
                                                    'class'=>'chosen-zalog-subcategory-select subcategory-'.$lot->id.'-load form-control form-control-sm', 
                                                    'data-placeholder'=>'Все подкатегории', 
                                                    'data-lotid'=>$lot->id, 
                                                    'disabled' => $lotSubCategoryDisable,
                                                    'multiple' => true,
                                                    'tabindex'=>'2',
                                                    'options' => $lotSubCategorySelect
                                                ])
                                                ->label('Подкатегория');?>
                                </li>
                            </ul>
                        <?php ActiveForm::end() ?>

                        <hr>

                        <ul class="item-meta lot-block__info">
                            <li><a href="<?=Url::to(['user/lot-remove'])?>" data-lotid="<?=$lot->id?>" class="remove-zalog-lot btn btn-primary text-white">Удалить</a></li>
                            <li><a href="javascript:void(0);" class="btn btn-success text-white-50">Редактировать</a></li>
                            <li><a href="<?=Url::to(['user/lot-status'])?>" data-lotid="<?=$lot->id?>" class="status-zalog-lot btn btn-secondary <?=($lot->status)? 'text-white':'text-white-50'?>"><?=($lot->status)? 'Снять с публикации' : 'Опубликовать' ?></a></li>
                        </ul>

                        <p class="mt-3">Цена: <span class="h6 line-1 <?=$priceClass?> font16"><?= Yii::$app->formatter->asCurrency($lot->lotPrice)?></span> <span class="text-muted mr-5"><?= ($lot->lotOldPrice)? Yii::$app->formatter->asCurrency($lot->lotOldPrice) : '' ?></span></p>
                    </figcaption>
                </div>
                
            </div>     
    </figure>