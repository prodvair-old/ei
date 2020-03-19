<?php
use yii\helpers\Url;

use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Bankrupts;
use common\models\Query\Lot\Managers;
use common\models\Query\Lot\Sro;

$lotsCount          = Lots::find()->count();
$doljnikCount       = Bankrupts::find()->count();
$arbitrsCount       = Managers::find()->where(['typeId' => 1])->count();
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
        <loc><?= $host.Url::to('/sitemap-lots_filter_bunkrupt.xml') ?></loc>
    </sitemap>
    <sitemap>
        <loc><?= $host.Url::to('/sitemap-lots_filter_arrest.xml') ?></loc>
    </sitemap>
    <sitemap>
        <loc><?= $host.Url::to('/sitemap-lots_filter_zalog.xml') ?></loc>
    </sitemap>
    <?php
        // Лоты
        $limit = 0;
        $count = $lotsCount;
        while ($count > 0) {
            $count = $count - 1000;
            if ($count > 0) {
                $limit = $limit + 1000;
            } else {
                $limit = $lotsCount;
            }
    ?>
    <sitemap>
        <loc><?= $host.'/sitemap-lots-'.$limit.'.xml' ?></loc>
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