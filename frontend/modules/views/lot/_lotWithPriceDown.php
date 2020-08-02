<?php

use common\models\db\Lot;
use frontend\modules\components\LotBlockSmall;

/* @var $lots Lot */

if ($lots) : ?>
    <?php foreach ($lots as $lot) : ?>
        <div class="col-lg-3 col-sm-6 mb-40" itemscope itemtype="http://schema.org/Product">
            <?= LotBlockSmall::widget(['lot' => $lot, 'url' => $url]) ?>
        </div>
    <?php endforeach; ?>
    <div class="col-lg-3 col-sm-6 mb-40 lot_next__btn">
        <a href="/bankrupt/lot-list?LotSearch%5Bsearch%5D=&LotSearch%5Bregion%5D=&LotSearch%5BminPrice%5D=&LotSearch%5BmaxPrice%5D=&LotSearch%5Betp%5D=&LotSearch%5BtradeType%5D=&LotSearch%5BandArchived%5D=0&LotSearch%5BhaveImage%5D=0&LotSearch%5BhasReport%5D=0&LotSearch%5BpriceDown%5D=1&LotSearch%5Befrsb%5D=&LotSearch%5BbankruptName%5D=&LotSearch%5BtorgDateRange%5D=&LotSearch%5BstartApplication%5D=0&LotSearch%5BcompetedApplication%5D=0" class="btn btn-primary borr-10">
            Больше предложений
            <i class="fa fa-arrow-right"></i>
        </a>
    </div>
<?php else: ?>
    <div class="col-md-12">
        <h5>Не удалось найти лоты cо сниженной ценой</h5>
    </div>
<?php endif; ?>

