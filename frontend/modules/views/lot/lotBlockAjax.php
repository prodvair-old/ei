<?php

use frontend\components\NumberWords;

/* @var $lots \common\models\db\Lot */

$priceClass = 'text-secondary';

?>

<?php foreach ($lots as $lot) : ?>

<figure class="tour-<?= $type ?>-item-01" itemscope itemtype="http://schema.org/Product">
    <a href="javascript:void()">

        <?=($type == 'long')? '<div class="d-flex flex-column flex-sm-row">' : ''?>

        <?=($type == 'long')? '<div>' : ''?>

        <!--        --><?//= $lot->getImage('thumb') ?>

        <div class="image image-galery">
            <img src="<?= $lot->getImage('thumb') ?>" onError="this.src='img/img.svg'"/>
            <div class="image-galery__control"></div>

        </div>
        <?= ($type == 'long') ? '</div>' : '' ?>

        <?= ($type == 'long') ? '<div>' : '' ?>
        <figcaption class="content">
            <ul class="item-meta lot-block__info">
                <!--                <span class="--><? //= $lotTypeClass ?><!--"><li>-->
                <? //= $lotType ?><!--</li></span>-->
                <!--                --><? //= ($lotOrganizatioun)? "<li>$lotOrganizatioun</li>" : '' ?>
            </ul>
            <hr>
            <h3 class="lot-block__title <?= (!empty($lot->archive)) ? ($lot->archive) ? 'text-muted' : '' : '' ?>"
                itemprop="name"><?= $lot->title ?> <?= (!empty($lot->archive)) ? ($lot->archive) ? '<span class="text-primary">(Архив)</span>' : '' : '' ?></h3>

            <hr>
            <ul class="item-meta lot-block__info">
                <!--                <li>--><?//= $lot->id ?><!--</li>-->
                <!--                <li>--><?//= $lot->torg->etp->title ?><!--</li>-->
                <li><?= Yii::$app->formatter->asDate($lot->torg->published_at, 'long') ?></li>
                <li>
                    <div class="rating-item rating-sm rating-inline clearfix">
                        <!-- <div class="rating-icons">
                            <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                        </div> -->
                        <!--                        <p class="rating-text font600 text-muted font-12 letter-spacing-1">-->
                        <? //=NumberWords::widget(['number' => $lot->viewsCount, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?><!--</p>-->
                    </div>
                </li>
                <li>
                    <!--                    <div --><? //=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" class="wish-star" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star" data-id="'.$lot->id.'" data-type="'.$lot->torg->type.'"'?>
                    <!--                        <img src="img/star-->
                    <? //=($lot->getWishId(\Yii::$app->user->id))? '' : '-o' ?><!--.svg" alt="">-->
                    <!--                    </div>-->
                </li>
            </ul>
            <!--            --><? // if ($lot->torg->type == 'zalog') { ?>
            <!--                <hr>-->
            <!--                <ul class="item-meta lot-block__info">-->
            <!--                    <li>-->
            <!--                        Организация: <span class="-->
            <? //=($lot->archive)? 'text-muted' : '' ?><!--"> -->
            <? //= isset($lot->torg->owner) ? $lot->torg->owner->title : '' ?><!--</span>-->
            <!--                    </li>-->
            <!--                </ul>-->
            <!--            --><? // } ?>
            <hr>

            <ul class="item-meta lot-block__info">
                Категория:&nbsp;
                <?php foreach ($lot->categories as $item) : ?>
                    <li>
                        <span itemprop="category">   <?= $item->name ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <hr>
            <p class="mt-3"><span
                        class="h6 line-1 <?= $priceClass ?> font16" <?= ($color) ? 'style="color: ' . $color . '!important"' : '' ?> itemprop="price"><?= Yii::$app->formatter->asCurrency($lot->start_price) ?></span>
                <span class="text-muted mr-5"></span></p>
        </figcaption>
        <?= ($type == 'long') ? '</div>' : '' ?>

        <?= ($type == 'long') ? '</div>' : '' ?>
    </a>

</figure>

<?= ($type == 'grid') ? '</div>' : '' ?>

<?php endforeach;