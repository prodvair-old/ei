<?php
use frontend\components\NumberWords;

$priceClass = 'text-secondary';
try {
    if ($lot->LotTradeType == 'PublicOffer') {
        $priceClass = 'text-primary';
    } else if ($lot->LotTradeType == 'OpenedAuction') {
        $priceClass = 'text-success';
    }
} catch (\Throwable $th) {
    $priceClass = 'text-primary';
}

?>
<div class="col">
                
    <figure class="tour-grid-item-01">

        <a href="<?=$lot->lotUrl?>">
        
            <div class="image">
                <img src="<?=$lot->lotImage[0]?>" alt="" />
            </div>
            
            <figcaption class="content">
                <h5><?= $lot->lotTitle?></h5>
                <ul class="item-meta">
                    <li><?= Yii::$app->formatter->asDate($lot->lotPublication, 'long')?></li>
                    <li>	
                        <div class="rating-item rating-sm rating-inline clearfix">
                            <div class="rating-icons">
                                <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                            </div>
                            <?$view = 1446?>
                            <p class="rating-text font600 text-muted font-12 letter-spacing-1"><?=NumberWords::widget(['number' => 543, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?></p>
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
        
        </a>
        
    </figure>
    
</div>