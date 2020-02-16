<?php
use frontend\components\NumberWords;

$priceClass = 'text-secondary';
try {
    if ($lot->torg->tradeTypeId == 1) {
        $priceClass = 'text-primary';
    } else if ($lot->torg->tradeTypeId == 2) {
        $priceClass = 'text-primary';
    }
} catch (\Throwable $th) {
    $priceClass = 'text-primary';
}

if ($lot->torg->typeId == 1) {
    $lotType = 'Банкротное имущество';
    $lotTypeClass = 'lot__bankrupt';
} else if ($lot->torg->typeId == 2){
    $lotType = 'Арестованное имущество';
    $lotTypeClass = 'lot__arest';
} else if ($lot->torg->typeId == 3){
    $lotType = 'Имущество организации';
    $lotTypeClass = 'lot__zalog';
    $lotOrganizatioun = $lot->torg->owner->title;
}

?>

<?
$isCategory = $lot->category->categoryId == '1061' ||
$lot->category->categoryId == '1063' ||
$lot->category->categoryId == '1064' ||
$lot->category->categoryId == '1068' ||
$lot->category->categoryId == '1083' ||
$lot->category->categoryId == '1102' ||
$lot->category->categoryId == '1102'; 
?>
<?=($type == 'grid')? '<div class="col">': ''?>
                
    <figure class="tour-<?=$type?>-item-01">
        <a href="<?=$lot->url?>" target="_blank">

            <?=($type == 'long')? '<div class="d-flex flex-column flex-sm-row">' : ''?>
        
                <?=($type == 'long')? '<div>' : ''?>
               
                    <div class="image image-galery">

                        <?if(
                            !$lot[info][address][geo_lat] && 
                            !$lot[info][address][geo_lon] &&
                            !$lot->images[0]['min'] || (!$isCategory && !$lot->images[0]['min'])):
                        ?>
                            <img src="img/img.svg" alt="" />
                        <?endif;?>

                        <?= ($lot->images[0]['min'])? '<img src="'.$lot->images[0]['min'].'" alt="" />'  : '';?>    
                        <?= ($lot->images[1]['min'])? '<img src="'.$lot->images[1]['min'].'" alt="" />'  : '';?>
                        <?= ($lot->images[2]['min'])? '<img src="'.$lot->images[2]['min'].'" alt="" />'  : '';?>
                        <?= ($lot->images[3]['min'])? '<img src="'.$lot->images[3]['min'].'" alt="" />'  : '';?>
                        <?= ($lot->images[4]['min'])? '<img src="'.$lot->images[4]['min'].'" alt="" />'  : '';?>
                        <?if($isCategory):?>
                            <?= ($lot[info][address][geo_lat] && $lot[info][address][geo_lon])? '<img src="https://static-maps.yandex.ru/1.x/?ll='.$lot[info][address][geo_lon].','.$lot[info][address][geo_lat].'&size=300,250&z=16&l=sat&pt='.$lot[info][address][geo_lon].','.$lot[info][address][geo_lat].',pm2gnl" alt="" />'  : '';?>
                            <?= ($lot[info][address][geo_lat] && $lot[info][address][geo_lon])? '<img src="https://static-maps.yandex.ru/1.x/?ll='.$lot[info][address][geo_lon].','.$lot[info][address][geo_lat].'&size=300,250&z=13&l=sat&pt='.$lot[info][address][geo_lon].','.$lot[info][address][geo_lat].',pm2gnl" alt="" />'  : '';?>
                        <?endif;?>
                        <div class="image-galery__control"></div>

                    </div>
                <?=($type == 'long')? '</div>' : ''?>

                <?=($type == 'long')? '<div>' : ''?>
                    <figcaption class="content">
                        <ul class="item-meta lot-block__info">
                            <span class="<?= $lotTypeClass ?>"><li><?= $lotType ?></li></span>
                            <?= ($lotOrganizatioun)? "<li>$lotOrganizatioun</li>" : '' ?>
                        </ul>
                        <hr>
                        <h3 class="lot-block__title <?=(!empty($lot->archive))? ($lot->archive)? 'text-muted' : '' : ''?>"><?= $lot->title?> <?=(!empty($lot->archive))? ($lot->archive)? '<span class="text-primary">(Архив)</span>' : '' : ''?></h3>
                        
                        <hr>
                        <ul class="item-meta lot-block__info">
                            <li><?= Yii::$app->formatter->asDate($lot->torg->publishedDate, 'long')?></li>
                            <li>	
                                <div class="rating-item rating-sm rating-inline clearfix">
                                    <!-- <div class="rating-icons">
                                        <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                    </div> -->
                                    <p class="rating-text font600 text-muted font-12 letter-spacing-1"><?=NumberWords::widget(['number' => $lot->viewsCount, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?></p>
                                </div>
                            </li>
                            <li>
                                <div <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" class="wish-star" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star" data-id="'.$lot->id.'" data-type="'.$lot->torg->type.'"'?>>
                                    <img src="img/star<?=($lot->getWishId(Yii::$app->user->id))? '' : '-o' ?>.svg" alt="">
                                </div>
                            </li>
                        </ul>
                        <? if ($lot->torg->type == 'zalog') { ?>
                        <hr>
                        <ul class="item-meta lot-block__info">
                            <li>
                                Организация: <span class="<?=($lot->archive)? 'text-muted' : '' ?>"> <?= $lot->torg->owner->title?></span>
                            </li>
                        </ul>
                        <? } ?>
                        <hr>
                        <ul class="item-meta lot-block__info">
                            <li>
                                Категория: <span class="<?=($lot->archive)? 'text-muted' : '' ?>"> <?= $lot->category->name?></span>
                            </li>
                        </ul>
                        <hr>
                        <p class="mt-3"><span class="h6 line-1 <?=$priceClass?> font16" <?=($color)? 'style="color: '.$color.'!important"': ''?>><?= Yii::$app->formatter->asCurrency($lot->price)?></span> <span class="text-muted mr-5"><?= ($lot->oldPrice)? Yii::$app->formatter->asCurrency($lot->oldPrice) : '' ?></span></p>
                    </figcaption>
                <?=($type == 'long')? '</div>' : ''?>
                
            <?=($type == 'long')? '</div>' : ''?>

            

        </a>
        
    </figure>

<?=($type == 'grid')? '</div>' : ''?>
