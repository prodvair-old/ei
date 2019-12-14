<?php
use frontend\components\NumberWords;

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

?>
<?=($type == 'grid')? '<div class="col">': ''?>
                
    <figure class="tour-<?=$type?>-item-01">

        <a href="<?=$lot->lotUrl?>" target="_blank">

            <?=($type == 'long')? '<div class="d-flex flex-column flex-sm-row align-items-xl-center">' : ''?>
        
                <?=($type == 'long')? '<div>' : ''?>
                    <div class="image image-galery">
                        <img src="<?=($lot->lotImage)? $lot->lotImage[0] : 'img/img.svg'?>" alt="" />
                        <?= ($lot->lotImage[1])? '<img src="'.$lot->lotImage[1].'" alt="" />'  : ''?>
                        <?= ($lot->lotImage[2])? '<img src="'.$lot->lotImage[2].'" alt="" />'  : ''?>
                        <?= ($lot->lotImage[3])? '<img src="'.$lot->lotImage[3].'" alt="" />'  : ''?>
                        <?= ($lot->lotImage[4])? '<img src="'.$lot->lotImage[4].'" alt="" />'  : ''?>
                        <div class="image-galery__control"></div>
                    </div>
                <?=($type == 'long')? '</div>' : ''?>

                <?=($type == 'long')? '<div>' : ''?>
                    <figcaption class="content">
                        <h3 class="lot-block__title <?=(!empty($lot->lot_archive))? ($lot->lot_archive)? 'text-muted' : '' : ''?>"><?= $lot->lotTitle?> <?=(!empty($lot->lot_archive))? ($lot->lot_archive)? '<span class="text-primary">(Архив)</span>' : '' : ''?></h3>
                        <hr>
                        <ul class="item-meta lot-block__info">
                            <li><?= Yii::$app->formatter->asDate($lot->lot_timepublication, 'long')?></li>
                            <li>	
                                <div class="rating-item rating-sm rating-inline clearfix">
                                    <!-- <div class="rating-icons">
                                        <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                    </div> -->
                                    <p class="rating-text font600 text-muted font-12 letter-spacing-1"><?=NumberWords::widget(['number' => $lot->lotViews, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?></p>
                                </div>
                            </li>
                            <li>
                                <div <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" class="wish-star" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star" data-id="'.$lot->lotId.'" data-type="'.$lot->lotType.'"'?>>
                                    <img src="img/star<?=($lot->lotWishId)? '' : '-o' ?>.svg" alt="">
                                </div>
                            </li>
                        </ul>
                        <? if ($lot->lotType == 'zalog') { ?>
                        <hr>
                        <ul class="item-meta lot-block__info">
                            <li>
                                Организация: <span class="<?=($lot->lot_archive)? 'text-muted' : '' ?>"> <?= $lot->owner->name?></span>
                            </li>
                        </ul>
                        <? } ?>
                        <hr>
                        <ul class="item-meta lot-block__info">
                            <li>
                                Категория: <span class="<?=($lot->lot_archive)? 'text-muted' : '' ?>"> <?= $lot->LotCategory[0]?></span>
                            </li>
                        </ul>
                        <hr>
                        <p class="mt-3">Цена: <span class="h6 line-1 <?=$priceClass?> font16"><?= Yii::$app->formatter->asCurrency($lot->lotPrice)?></span> <span class="text-muted mr-5"><?= ($lot->lotOldPrice)? Yii::$app->formatter->asCurrency($lot->lotOldPrice) : '' ?></span></p>
                    </figcaption>
                <?=($type == 'long')? '</div>' : ''?>
                
            <?=($type == 'long')? '</div>' : ''?>

        </a>
        
    </figure>

<?=($type == 'grid')? '</div>' : ''?>
