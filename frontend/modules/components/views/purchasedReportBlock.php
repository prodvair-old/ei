<?

use common\models\db\Lot;
use common\models\db\Report;
use frontend\modules\forms\ReportForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $reports Report[] */
/* @var $reportForm ReportForm */

?>
<?php foreach ($reports as $report) : ?>
    <div class="report mb-40">
        <div class="report__head">
            <div class="report__head__name">Отчет эксперта</div>
        </div>
        <div class="report__body row">
            <div class="col-md-6 report__body__expert">
                <?= $report->user->getAvatar(['class' => "report__body__expert-img"]) ?>
                <span class="report__body__expert__name">
                    <?= $report->user->profile->first_name ?>
                    <br>
                    <?= $report->user->profile->last_name ?>
                </span>
            </div>
            <div class="col-md-6 report__body__diogramm">
                <div class="report__body__diogramm__green">
                    <svg class="report__body__diogramm__green-svg" width="70" height="70">
                        <circle
                                class="report__body__diogramm__green-circle--back"
                                stroke="#BDBDBD"
                                stroke-width="5"
                                cx="35"
                                cy="35"
                                r="30"
                                fill="transparent"
                        />
                        <circle
                                class="report__body__diogramm__green-circle"
                                id="green-circle-<?= $report->id ?>"
                                stroke="#077751"
                                stroke-width="5"
                                cx="35"
                                cy="35"
                                r="30"
                                fill="transparent"
                        />
                    </svg>

                    <div class="report__body__diogramm__green__info">
                        <div class="report__body__diogramm__green__info__number">
                            <span><?= $report->attraction ?></span>/10
                        </div>
                        <div class="report__body__diogramm__green__info__name">
                            Интерес
                        </div>
                    </div>

                    <script>
                        // не забывать подтсавлять номер для id
                        var circle = document.querySelector('#green-circle-<?=$report->id?>');
                        var r = circle.r.baseVal.value;
                        var circumference = 2 * Math.PI * r;

                        circle.style.strokeDasharray = `${circumference} ${circumference}`;
                        circle.style.strokeDashoffset = circumference;

                        function setProgress(percent) {
                            const offset = circumference - percent / 100 * circumference;
                            circle.style.strokeDashoffset = offset;
                        }

                        setProgress(<?= $report->attraction ?>0);
                    </script>
                </div>

                <div class="report__body__diogramm__red">
                    <svg class="report__body__diogramm__red-svg" width="70" height="70">
                        <circle
                                class="report__body__diogramm__red-circle--back"
                                stroke="#BDBDBD"
                                stroke-width="5"
                                cx="35"
                                cy="35"
                                r="30"
                                fill="transparent"
                        />
                        <circle
                                class="report__body__diogramm__red-circle"
                                id="red-circle-<?= $report->id ?>"
                                stroke="#EB5757"
                                stroke-width="5"
                                cx="35"
                                cy="35"
                                r="30"
                                fill="transparent"
                        />
                    </svg>

                    <div class="report__body__diogramm__red__info">
                        <div class="report__body__diogramm__red__info__number">
                            <span><?= $report->risk ?></span>/10
                        </div>
                        <div class="report__body__diogramm__red__info__name">
                            риск
                        </div>
                    </div>

                    <script>
                        // не забывать подтсавлять номер для id
                        var circle = document.querySelector('#red-circle-<?=$report->id?>');
                        var r = circle.r.baseVal.value;
                        var circumference = 2 * Math.PI * r;

                        circle.style.strokeDasharray = `${circumference} ${circumference}`;
                        circle.style.strokeDashoffset = circumference;

                        function setProgress(percent) {
                            const offset = circumference - percent / 100 * circumference;
                            circle.style.strokeDashoffset = offset;
                        }

                        setProgress(<?= $report->risk ?>0);
                    </script>
                </div>

            </div>
            <div class="col-md-6 report__body__images">
                <div class="report__body__images__slider">
                    <?php
                    $image = $report->getImage('original');
                    if ($image) :
                        while ($image) : ?>
                            <div class="report__body__images__slider__item">
                                <img src="<?= $image ?>" alt="Images">
                            </div>
                            <?php
                            $image = $report->getNextImage('original');
                        endwhile;
                    endif;
                    ?>
                </div>
            </div>
            <div class="col-md-6 report__body__info">
                <div class="report__body__info__title mb-20"><?= $report->title ?></div>
                <p class="report__body__info__text mb-30"><?= $report->content ?></p>
                <div class="report__body__info__price mb-20">Цена: <?= $report->cost ?> руб.</div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
