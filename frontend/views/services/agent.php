<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\faq\FaqFormAskQuestion;

$this->title = 'Услуги агента по торгам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container text-center">
</div>


<section class="page-wrapper pb-50">
    <div class="container">
        <h1 class="pt-30 pb-20 text-center"><?= Html::encode($this->title) ?></h1>

        <div class="row">
        
            <div class="col-lg-12 service-tab">
                <h4>
                    Услуги агента по торгам
                </h4>
                <p>
                    Участие в торгах через Агента — максимум преимуществ, безопасность от приобретения мусорных лотов, отклонения заявки, отказа в регистрации на бирже, потери денег из-за неверно выбранной тактики и других «подводных камней».<br>

                    Агент действует на торгах от вашего имени. Он использует собственную электронную цифровую подпись, свою аккредитацию на площадке. Законность участия в торгах через доверенное лицо определяется 1005 статьёй ГК РФ.<br>

                    Делегирование Агенту своих полномочий на торгах — эффективное решение для тех, кто никогда не участвовал в торгах, чей опыт оказался неудачным или не совсем удачным, кому нужно срочно совершить покупку, а времени на оформление ЭП, аккредитации на площадке и подачи заявки (это несколько дней) — нет.<br>

                    Агент сможет приобрести для вас имущество по выгодной стоимости.
                </p>
            </div>

            <div class="col-lg-12 service-tab">
                <h4>
                    Ваши преимущества участия в торгах через Агента:
                </h4>
                <p>
                    <ul>
                        <li>
                            вам не нужно покупать электронную подпись — Агент использует собственную, которую принимают на всех торговых площадках;
                        </li>
                        <li>
                            ваше участие гарантировано — мы знаем, как составлять заявку с полным пакетом документов, которую принимают;
                        </li>
                        <li>
                            вы можете участвовать в торгах на любой площадке без аккредитации — Агент аккредитован на всех площадках РФ и будет использовать свою учётную запись для участия;
                        </li>
                        <li>
                            мы защитим вас в УФАС — бывает, что заявку отклоняют неправомерно. В этом случае мы составляем и подаём жалобу в УФАС для защиты ваших интересов;
                        </li>
                        <li>
                            наши услуги для вас доступны и выгодны — стоимость нашей работы немногим дороже стоимости электронной подписи и регистрации на платных торговых площадках. А наш опыт, стратегии обязательно принесут вам успех и выгоду.
                        </li>
                    </ul>
                </p>
            </div>
        </div>
    </div>
    
</section>


<!-- <section class="page-wrapper pt-50 pb-50">
    <?=FaqFormAskQuestion::widget()?>
</section> -->
