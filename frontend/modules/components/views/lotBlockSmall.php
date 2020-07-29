<?php

use common\models\db\Lot;
use common\models\db\Torg;
use common\models\db\WishList;
use common\models\db\Stat;
use sergmoro1\lookup\models\Lookup;
use yii\helpers\Html;

/* @var $lot Lot */
/* @var $url */
/* @var $type */

$priceClass = 'text-secondary';
$lotOrganizatioun = '';

if ($lot->torg->property == 1) {
    $lotType = 'Банкротное имущество';
    $lotTypeUrl = 'bankrupt';
} else if ($lot->torg->property == 2) {
    $lotType = 'Арестованное имущество';
    $lotTypeUrl = 'arrest';
} else if ($lot->torg->property == 3) {
    $lotType = 'Имущество организации';
    $lotTypeUrl = 'zalog';
} else if ($lot->torg->property == 4) {
    $lotType = 'Муниципально имущество';
    $lotTypeUrl = 'municipal';
}

$image = $lot->getImage('thumb');

$darkClass = '';

if (!$image) {
    $darkClass = 'dark';
}

$wishListCheck = WishList::find()->where(['lot_id' => $lot->id, 'user_id' => \Yii::$app->user->id])->one();
$wishListAll = WishList::find()->where(['lot_id' => $lot->id])->count();
?>
<div class="col-lg-3 col-sm-6 mb-40" itemscope itemtype="http://schema.org/Product">
    <a href="<?= $lotTypeUrl . '/' .((empty( $lot->categories[0]->slug))? 'lot-list' :  $lot->categories[0]->slug ) . '/' . $lot->id ?>"
        target="_blank" class="lot__block">
        <div class="lot__block__img" style>
            <div class="lot__block__img__property lot__block__img__property-<?=$lot->torg->property?>"><?= $lotType ?>
            </div>
            <?= (!empty($lot->archive)) ? ($lot->archive) ? '<div class="lot__block__img__archive">Архив</div>' : '' : '' ?>
            <div class="lot__block__img__report">
                <img src="./img/check-report.svg" alt="">
            </div>
            <div
                <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" class="lot__block__img__favorite '.$darkClass.'" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js lot__block__img__favorite '.$darkClass.'" data-id="'.$lot->id.'"'?>>
                <span><?=$wishListAll?></span>
                <?if ($wishListCheck->lot_id) : ?>
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 47.94 47.94"
                    style="enable-background:new 0 0 47.94 47.94;" xml:space="preserve">
                    <path style="fill:#FFB436;" d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757
                                c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042
                                c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685
                                c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528
                                c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956
                                C22.602,0.567,25.338,0.567,26.285,2.486z" />
                </svg>
                <? else : ?>
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 49.94 49.94"
                    style="enable-background:new 0 0 49.94 49.94;" xml:space="preserve">
                    <path class="lot__block__img__favorite-path"
                        d="M48.856,22.731c0.983-0.958,1.33-2.364,0.906-3.671c-0.425-1.307-1.532-2.24-2.892-2.438l-12.092-1.757
                                        c-0.515-0.075-0.96-0.398-1.19-0.865L28.182,3.043c-0.607-1.231-1.839-1.996-3.212-1.996c-1.372,0-2.604,0.765-3.211,1.996
                                        L16.352,14c-0.23,0.467-0.676,0.79-1.191,0.865L3.069,16.623C1.71,16.82,0.603,17.753,0.178,19.06
                                        c-0.424,1.307-0.077,2.713,0.906,3.671l8.749,8.528c0.373,0.364,0.544,0.888,0.456,1.4L8.224,44.702
                                        c-0.232,1.353,0.313,2.694,1.424,3.502c1.11,0.809,2.555,0.914,3.772,0.273l10.814-5.686c0.461-0.242,1.011-0.242,1.472,0
                                        l10.815,5.686c0.528,0.278,1.1,0.415,1.669,0.415c0.739,0,1.475-0.231,2.103-0.688c1.111-0.808,1.656-2.149,1.424-3.502
                                        L39.651,32.66c-0.088-0.513,0.083-1.036,0.456-1.4L48.856,22.731z M37.681,32.998l2.065,12.042c0.104,0.606-0.131,1.185-0.629,1.547
                                        c-0.499,0.361-1.12,0.405-1.665,0.121l-10.815-5.687c-0.521-0.273-1.095-0.411-1.667-0.411s-1.145,0.138-1.667,0.412l-10.813,5.686
                                        c-0.547,0.284-1.168,0.24-1.666-0.121c-0.498-0.362-0.732-0.94-0.629-1.547l2.065-12.042c0.199-1.162-0.186-2.348-1.03-3.17
                                        L2.48,21.299c-0.441-0.43-0.591-1.036-0.4-1.621c0.19-0.586,0.667-0.988,1.276-1.077l12.091-1.757
                                        c1.167-0.169,2.176-0.901,2.697-1.959l5.407-10.957c0.272-0.552,0.803-0.881,1.418-0.881c0.616,0,1.146,0.329,1.419,0.881
                                        l5.407,10.957c0.521,1.058,1.529,1.79,2.696,1.959l12.092,1.757c0.609,0.089,1.086,0.491,1.276,1.077
                                        c0.19,0.585,0.041,1.191-0.4,1.621l-8.749,8.528C37.866,30.65,37.481,31.835,37.681,32.998z" />
                </svg>
                <? endif; ?>
            </div>
            <?php
                if ($image) : ?>
            <div class="lot__block__img__image image image-galery">
                <?php
                        while ($image) {
                            echo Html::img($image, ['alt' => 'Images']);
                            $image = $lot->getNextImage('thumb');
                        }
                        ?>
                <div class="image-galery__control"></div>
            </div>
            <?php else : ?>
            <div class="lot__block__img__image image image-galery">
                <img src="img/img.svg" />
                <div class="image-galery__control"></div>
            </div>
            <?php endif; ?>
        </div>
        <div class="lot__block__info">
            <div class="lot__block__info__content">
                <div itemprop="category" class="lot__block__info__content__offer mb-15">
                    <?= Lookup::item('TorgOffer', $lot->torg->offer) ?>
                </div>
                <div itemprop="name"
                    class="lot__block__info__content__title mb-10 <?= (!empty($lot->archive)) ? ($lot->archive) ? 'text-muted' : '' : '' ?>">
                    <?= $lot->title ?>
                </div>
                <div itemprop="price" class="lot__block__info__content__price text-secondary mb-10">
                    <?= Yii::$app->formatter->asCurrency($lot->start_price) ?>
                    <?//<span class="text-muted"><del>880 000,00 ₽</del></span>?>
                </div>
            </div>
            <div class="lot__block__info__footer">
                <div class="lot__block__info__footer__published">
                    <?= Yii::$app->formatter->asDate($lot->torg->published_at, 'long') ?>
                </div>
                <div class="lot__block__info__footer__views">
                    0
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 511.999 511.999"
                        style="enable-background:new 0 0 511.999 511.999;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035
                                                c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201
                                                C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418
                                                c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418
                                                C447.361,287.923,358.746,385.406,255.997,385.406z" />
                            </g>
                        </g>
                        <g>
                            <g>
                                <path
                                    d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275
                                                s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516
                                                s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z" />
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </div>
            </div>
        </div>
    </a>
</div>