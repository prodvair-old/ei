<?php
use common\models\db\Lot;
use common\models\db\Report;
use frontend\modules\forms\ReportForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $reports Report[] */
/* @var $lot Lot */
/* @var $reportForm ReportForm */

if ($lot->torg->property == 1) {
    $lotTypeUrl = 'bankrupt';
} else if ($lot->torg->property == 2) {
    $lotTypeUrl = 'arrest';
} else if ($lot->torg->property == 3) {
    $lotTypeUrl = 'zalog';
} else if ($lot->torg->property == 4) {
    $lotTypeUrl = 'municipal';
}

?>
<?php foreach ($reports as $report) : ?>
    <div class="report mb-40">
        <div class="report__head">
            <div class="report__head__name font600 <?=($report->isPaid())? 'text-green' : ''?>">
                <?=($report->isPaid()) ? '<span class="elegent-icon-check_alt2" data-toggle="tooltip" title="Отчёт куплен"></span>': ''?>
                Отчет эксперта <?=($report->isPaid())? 'куплен | <a href="'.Url::to('/profile/purchase').'">Мои покупки</a>' : ''?> 
                
            </div>
            <a href="<?= $lotTypeUrl . '/' .((empty( $lot->categories[0]->slug))? 'lot-list' :  $lot->categories[0]->slug ) . '/' . $lot->id ?>" class="font600 report__head__number">Лот № <?= $lot->id ?></a>
        </div>
        <div class="report__body row bg-white borr-20 mt-30 pt-15 pb-15 pl-15 pr-15">
            <div class="col-md-6 report__body__expert mt-lg-0">
                <?= $report->user->getAvatar(['class' => "report__body__expert-img"]) ?>
                <span class="report__body__expert__name">
                    <?= $report->user->profile->first_name ?>
                    <br>
                    <?= $report->user->profile->last_name ?>
                    <br><small>Отчётов: <?=Report::find()->where(['user_id' => $report->user->id])->count()?></small>
                </span>
            </div>
            <div class="col-md-6 report__body__diogramm mt-lg-0">
                <div class="report__body__diogramm__green">
                    <svg class="report__body__diogramm__green-svg" width="70" height="70">
                        <circle
                                class="report__body__diogramm__green-circle--back"
                                stroke="#E0E0E0"
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
                                stroke="#E0E0E0"
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
        </div>
        <div class="report__body row">
            <div class="col-12 report__body__info">
                <div class="report__body__info__title"><?= $report->title ?></div>
            </div>
            <div class="col-md-6 report__body__images pl-0">
                <div class="report__body__images__slider <?= ($report->isPaid())? 'zoom-gallery' : ''?>">
                    
                    <?php
                    if ($report->isPaid()) :
                        $image = $report->getImage('original');
                        if ($image) :
                            while ($image) : ?>
                                <div href="<?= $image ?>" class="report__body__images__slider__item">
                                    <img src="<?= $image ?>">
                                </div>
                                <?php
                                $image = $report->getNextImage('original');
                            endwhile;
                        endif;
                    else : ?>
                        <div class="report__body__images__slider__item lock">
                            <img src="/img/lock.png">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6 report__body__info">
                <div class="report__body__info__text">
                    <?php if ($report->isPaid()) : ?>
                        <?= $report->content ?>
                    <?php else : ?>
                        <p class="bg-white font-sm borr-5">&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <p class="bg-white font-sm borr-5">&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <p class="bg-white font-sm borr-5">&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <p class="bg-white font-sm borr-5">&nbsp;&nbsp;&nbsp;</p>
                    <?php endif; ?>
                </div>
                <?php $form = ActiveForm::begin(['action' => Url::to(['/lot/lot/invoice']), 'options' => [
                    'class' => 'invoice-form mt-30'
                ]]); ?>
                <?php if (!Yii::$app->user->isGuest) : ?>
                    <?php if (!$report->isPaid()) : ?>
                        <?= $form->field($reportForm, 'reportId')->hiddenInput(['value' => $report->id])->label(false); ?>
                        <?= $form->field($reportForm, 'cost')->hiddenInput(['value' => $report->cost])->label(false); ?>
                        <?= $form->field($reportForm, 'userId')->hiddenInput(['value' => Yii::$app->user->identity->getId()])->label(false); ?>
                        <?= $form->field($reportForm, 'returnUrl')->hiddenInput(['value' => Yii::$app->request->absoluteUrl])->label(false); ?>
                        <?= Html::submitButton('Купить отчёт за '.$report->cost.' руб.', [
                            'class' => 'btn btn-primary btn-block text-white borr-10', 'name' => 'invoice-button'
                        ]) ?>
                        <div class="custom-control custom-checkbox mt-10">
                            <div class="form-group field-checkPolicy">
                                <input type="hidden" name="ReportForm[checkPolicy]" value="0">
                                <input type="checkbox" id="checkPolicy" checked class="custom-control-input" required name="ReportForm[checkPolicy]" value="1">
                                <label class="custom-control-label" for="checkPolicy">Согласен с <a href="/policy" target="_blank">пользовательскими условиями</a></label>
                            </div>
                        </div>
                        
                    <?php endif; ?>
                <?php else : ?>
                    <div class="report__body__info__price mb-20">Цена отчёта: <?= $report->cost ?> руб.</div>
                    <p>Что бы купить отчет - <a href="#loginFormTabInModal-login" data-toggle="modal"
                                                data-target="#loginFormTabInModal"
                                                data-backdrop="static" data-keyboard="false">Войдите
                        </a> или
                        <a href="#loginFormTabInModal-register" data-toggle="modal"
                           data-target="#loginFormTabInModal"
                           data-backdrop="static" data-keyboard="false">
                            Зарегистрируйтесь
                        </a></p>
                <?php endif; ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

