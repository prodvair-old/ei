<?php
use frontend\components\NumberWords;

$priceClass = 'text-secondary';
try {
    if ($lot->torgy_tradetype == 'PublicOffer') {
        $priceClass = 'text-primary';
    } else if ($lot->torgy_tradetype == 'OpenedAuction') {
        $priceClass = 'text-success';
    }
} catch (\Throwable $th) {
    $priceClass = 'text-primary';
}

?>
<?=($type == 'grid')? '<div class="col">': ''?>
                
    <figure class="tour-<?=$type?>-item-01">

        <a href="<?=$lot->lotUrl?>">

            <?=($type == 'long')? '<div class="d-flex flex-column flex-sm-row align-items-xl-center">' : ''?>
        
                <?=($type == 'long')? '<div>' : ''?>
                    <div class="image">
                        <img src="<?=$lot->lotImage[0]?>" alt="" />
                    </div>
                <?=($type == 'long')? '</div>' : ''?>

                <?=($type == 'long')? '<div>' : ''?>
                    <figcaption class="content">
                        <h5><?= $lot->lotTitle?></h5>
                        <ul class="item-meta">
                            <li><?= Yii::$app->formatter->asDate($lot->lot_timepublication, 'long')?></li>
                            <li>	
                                <div class="rating-item rating-sm rating-inline clearfix">
                                    <div class="rating-icons">
                                        <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                    </div>
                                    <?$view = 1446?>
                                    <p class="rating-text font600 text-muted font-12 letter-spacing-1"><?=NumberWords::widget(['number' => $lot->lotViews, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?></p>
                                </div>
                            </li>
                        </ul>
                        <ul class="item-meta mt-15">
                            <li>
                                Категория: <span class="font700 h6"> <?= $lot->LotCategory[0]?></span>
                            </li>
                        </ul>
                        <p class="mt-3">Цена: <span class="h6 line-1 <?=$priceClass?> font16"><?= Yii::$app->formatter->asCurrency($lot->lotPrice)?></span> <span class="text-muted mr-5"><?= ($lot->lotOldPrice)? Yii::$app->formatter->asCurrency($lot->lotOldPrice) : '' ?></span></p>
                    </figcaption>
                <?=($type == 'long')? '</div>' : ''?>
                
            <?=($type == 'long')? '</div>' : ''?>
            
        </a>
        
    </figure>

<?=($type == 'grid')? '</div>' : ''?>
