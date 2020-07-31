<?

use common\models\db\Report;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $reports Report[] */

?>
<style>
    .report-block-wrapper {
        background-color: #e4f3f8;
    }

    .ei-green {
        color: #077751;
    }

    .ei-report-int {
        font-size: 3.2em;
    }

    .cs-blur {
        filter: blur(10px);
    }
</style>
<div class="col-md-12 mb-30">
    <?php foreach ($reports as $report) : ?>
        <div class="col-md-12 report-block-wrapper mb-20">
            <div class="row">
                <div class="col-md-6"><p class="ei-green"><b>Отчет эксперта</b></p></div>
            </div>
            <div class="col-md-12 mt-40">
                <div class="row">
                    <div class="col-md-8">
                        <div class="col-md-12">
                            <div class="row mb-5">
                                <div class="col-md-5 bg-white mr-5">
                                    <?= $report->user->getFullName() ?>
                                </div>
                                <div class="col-md-3 bg-white mr-5">
                                    <span>Риски</span>
                                    <span class="ei-green ei-report-int"><?= $report->risk ?></span>&nbsp;/10
                                </div>
                                <div class="col-md-3 bg-white mr-5">
                                    <span>Интерес</span>
                                    <p><span class="ei-green ei-report-int"><?= $report->attraction ?></span>&nbsp;/10
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-30">
                    <div class="row">
                        <div class="col-md-8 bg-white">
                            <?php
                            $image = $report->getImage('original');
                            if ($image) : ?>
                                <div class="fotorama mt-20 mb-40 <?= ($report->isPaid()) ? '' : 'cs-blur' ?>"
                                     data-allowfullscreen="true" data-nav="thumbs"
                                     data-arrows="always" data-click="true">
                                    <?php
                                    while ($image) {
                                        echo Html::img($image, ['alt' => 'Images']);
                                        $image = $report->getNextImage('original');
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-12">
                                <b><?= $report->title ?></b>
                            </div>
                            <div class="col-md-12">
                                <?= $report->content ?>
                            </div>
                            <div class="col-md-12 mt-20">
                                <p class="ei-green"><b>Цена: <?= $report->cost ?> руб.</b></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-12">&nbsp;</div>
        </div>

    <?php endforeach; ?>
</div>