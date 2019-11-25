<?php
use yii\helpers\Url;

use common\models\Query\Arrest\LotsArrest;
use common\models\Query\Bankrupt\Lots;
use common\models\Query\Bankrupt\Bankrupts;
use common\models\Query\Bankrupt\Arbitrs;
use common\models\Query\Bankrupt\Sro;

$lotsArrestCount    = LotsArrest::find()->joinWith('torgs')->where('torgs."trgExpireDate" >= NOW()')->count();
$lotsBankruptCount  = Lots::find()->joinWith('torgy')->where('torgy.timeend >= NOW()')->count();
$doljnikCount       = Bankrupts::find()->count();
$arbitrsCount       = Arbitrs::find()->count();
$sroCount           = Sro::find()->count();

?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc><?= $host.Url::to('/sitemap-pages.xml') ?></loc>
    </sitemap>
    <sitemap>
        <loc><?= $host.Url::to('/sitemap-service.xml') ?></loc>
    </sitemap>
    <sitemap>
        <loc><?= $host.Url::to('/sitemap-lots_filter.xml') ?></loc>
    </sitemap>
    <?php
        // Лоты банкротки
        $limit = 0;
        $count = $lotsBankruptCount;
        while ($count > 0) {
            $count = $count - 1000;
            if ($count > 0) {
                $limit = $limit + 1000;
            } else {
                $limit = $lotsBankruptCount;
            }
    ?>
    <sitemap>
        <loc><?= $host.'/sitemap-lots_bankrupt-'.$limit.'.xml' ?></loc>
    </sitemap>
    <? } ?>
    <?php
        // Лоты аррестовки
        $limit = 0;
        $count = $lotsArrestCount;
        while ($count > 0) {
            $count = $count - 1000;
            if ($count > 0) {
                $limit = $limit + 1000;
            } else {
                $limit = $lotsArrestCount;
            }
    ?>
    <sitemap>
        <loc><?= $host.'/sitemap-lots_arrest-'.$limit.'.xml' ?></loc>
    </sitemap>
    <? } ?>
    <?php
        // Арбитражники
        $limit = 0;
        $count = $arbitrsCount;
        while ($count > 0) {
            $count = $count - 1000;
            if ($count > 0) {
                $limit = $limit + 1000;
            } else {
                $limit = $arbitrsCount;
            }
    ?>
    <sitemap>
        <loc><?= $host.'/sitemap-arbtr-'.$limit.'.xml' ?></loc>
    </sitemap>
    <? } ?>
    <?php
        // Должники
        $limit = 0;
        $count = $doljnikCount;
        while ($count > 0) {
            $count = $count - 1000;
            if ($count > 0) {
                $limit = $limit + 1000;
            } else {
                $limit = $doljnikCount;
            }
    ?>
    <sitemap>
        <loc><?= $host.'/sitemap-bnkr-'.$limit.'.xml' ?></loc>
    </sitemap>
    <? } ?>
    <?php
        // СРО
        $limit = 0;
        $count = $sroCount;
        while ($count > 0) {
            $count = $count - 1000;
            if ($count > 0) {
                $limit = $limit + 1000;
            } else {
                $limit = $sroCount;
            }
    ?>
    <sitemap>
        <loc><?= $host.'/sitemap-sro-'.$limit.'.xml' ?></loc>
    </sitemap>
    <? } ?>
</sitemapindex>