<?php

use common\models\db\Lot;
use common\models\db\Torg;
use common\models\db\WishList;
use sergmoro1\lookup\models\Lookup;
use yii\helpers\Html;

/* @var $lot Lot */
/* @var $url */
/* @var $type */

$priceClass = 'text-secondary';
$lotOrganizatioun = '';

if ($lot->torg->property == 1) {
    $lotType = 'Банкротное имущество';
    $lotTypeClass = 'lot__bankrupt';
} else if ($lot->torg->property == 2) {
    $lotType = 'Арестованное имущество';
    $lotTypeClass = 'lot__arest';
} else if ($lot->torg->property == 3) {
    $lotType = 'Имущество организации';
    $lotTypeClass = 'lot__zalog';
    $lotOrganizatioun = $lot->torg->owner->title;
} else if ($lot->torg->property == 4) {
    $lotType = 'Муниципально имущество';
    $lotTypeClass = 'lot__municipal';
}

$wishListCheck = WishList::find()->where(['lot_id' => $lot->id, 'user_id' => \Yii::$app->user->id])->one();
$wishListAll = WishList::find()->where(['lot_id' => $lot->id])->count();

?>
<?= ($type == 'grid') ? '<div class="col">' : '' ?>

<figure class="tour-<?= $type ?>-item-01" itemscope itemtype="http://schema.org/Product">
    <a href="<?= $url . '/' . $lot->id ?>" target="_blank">

        <?= ($type == 'long') ? '<div class="d-flex flex-column flex-sm-row">' : '' ?>
        <?= ($type == 'long') ? '<div>' : '' ?>

        <?php
        $image = $lot->getImage('thumb');
        if ($image) : ?>
            <div class="image image-galery">
                <?php
                while ($image) {
                    echo Html::img($image, ['alt' => 'Images']);
                    $image = $lot->getNextImage('thumb');
                }
                ?>
                <div class="image-galery__control"></div>
            </div>
        <?php else : ?>
            <div class="image image-galery">
                <img src="img/img.svg"/>
                <div class="image-galery__control"></div>
            </div>
        <?php endif; ?>

        <?= ($type == 'long') ? '</div>' : '' ?>

        <?= ($type == 'long') ? '<div>' : '' ?>
        <figcaption class="content">
            <ul class="item-meta lot-block__info">
                <span class="<?= $lotTypeClass ?>"><li><?= $lotType ?></li></span>
            </ul>
            <hr>
            <h3 class="lot-block__title <?= (!empty($lot->archive)) ? ($lot->archive) ? 'text-muted' : '' : '' ?>"
                itemprop="name"><?= $lot->title ?> <?= (!empty($lot->archive)) ? ($lot->archive) ? '<span class="text-primary">(Архив)</span>' : '' : '' ?></h3>

            <hr>
            <ul class="item-meta lot-block__info">
                <li><?= Yii::$app->formatter->asDate($lot->torg->published_at, 'long') ?></li>
                <li>
                    <div <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" class="wish-star  d-flex" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star d-flex" data-id="'.$lot->id.'"'?>>
                        <span class="pr-5"> <?=$wishListAll?> </span>
                        <img src="img/star<?=($wishListCheck->lot_id)? '' : '-o' ?>.svg" alt="">
                    </div>
                </li>
            </ul>
            <? if ($lot->torg->property == Torg::PROPERTY_ZALOG) : ?>
                <hr>
                <ul class="item-meta lot-block__info">
                    <li>
                        Организация: <span> <?= isset($lot->torg->owner) ? $lot->torg->owner->title : '' ?></span>
                    </li>
                </ul>
            <? endif; ?>
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
            <ul class="item-meta lot-block__info">
                Тип торгов:&nbsp;
                <li><span itemprop="category"> <?= Lookup::item('TorgOffer', $lot->torg->offer) ?></span></li>
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
