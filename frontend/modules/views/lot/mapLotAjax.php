<?php
use common\models\db\Lot;
use common\models\db\Torg;
use common\models\db\WishList;
use sergmoro1\lookup\models\Lookup;
use yii\helpers\Html;

foreach ($lots as $lot) {
    if ($lot->torg->property == 1) {
        $lotType = 'Банкротное имущество';
        $lotTypeClass = 'lot__bankrupt';
        $lotTypeName = 'bankrupt';
    } else if ($lot->torg->property == 2) {
        $lotType = 'Арестованное имущество';
        $lotTypeClass = 'lot__arest';
        $lotTypeName = 'arrest';
    } else if ($lot->torg->property == 3) {
        $lotType = 'Имущество организации';
        $lotTypeClass = 'lot__zalog';
        $lotTypeName = 'zalog';
        $lotOrganizatioun = $lot->torg->owner->title;
    } else if ($lot->torg->property == 4) {
        $lotType = 'Муниципально имущество';
        $lotTypeClass = 'lot__municipal';
        $lotTypeName = 'municipal';
    }
    
    $url = $lotTypeName.'/'.$lot->categories[0]->slug. '/' . $lot->id;
    
    $image = $lot->getImage('thumb');
    
    if ($image) {
        $img = $image[0];
    } else {
        $img = 'img/img.svg';
    }
?>
<a href="<?= $url?>" class="map__panel__content__block">
    <div class="map__panel__content__block__img">
        <img src="<?= $img ?>" alt="">
    </div>
    <div class="map__panel__content__block__info">
    <div class="map__panel__content__block__info-type <?= $lotTypeClass ?>"><?= $lotType ?></div>
        <h3 class="<?= (!empty($lot->archive)) ? ($lot->archive) ? 'text-muted' : '' : '' ?>"><?= $lot->title ?> <?= (!empty($lot->archive)) ? ($lot->archive) ? '<span class="text-primary">(Архив)</span>' : '' : '' ?></h3>
        <div class="map__panel__content__block__info__text">
            <div class="map__panel__content__block__info__text-trade">Тип тогов:  <?= Lookup::item('TorgOffer', $lot->torg->offer) ?></div>
            <div class="map__panel__content__block__info__text-price text-secondary"><?= Yii::$app->formatter->asCurrency($lot->start_price) ?></div>
            <div class="map__panel__content__block__info__text-date text-muted"><?= Yii::$app->formatter->asDate($lot->torg->published_at, 'long') ?></div>
        </div>
    </div>
</a>
<hr>
<?
}
?>