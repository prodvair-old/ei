<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\faq\FaqFormAskQuestion;

$this->title = 'Услуги агента по торгам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container text-center">
</div>


<section class="agent page-wrapper pb-50">

    <div class="container">

        <h1 class="pt-30 pb-20 text-center"><?= Html::encode($this->title) ?></h1>
        <p class="text-center agent__underheader">Торги по банкротству, торги арестованного имущества</p>

        <div class="row text-center">

            <div class="col-lg-3">
                <div class="agent__icon">
                    <i class="elegent-icon-profile text-primary"></i>
                </div>
                <p class="agent__header">
                    Гарантированное участие
                </p>
                <p class="agent__text">
                    У нас большой опыт в сфере электронных торгов. Мы подадим заявку на аукцион, которую не отклонят
                </p>
            </div>
            <div class="col-lg-3">
                <div class="agent__icon">
                    <i class="elegent-icon-profile text-primary"></i>
                </div>

                <p class="agent__header">
                    Участие в торгах на любой ЭТП
                </p>
                <p class="agent__text">
                    Мы аккредитованы на всех площадках и сможем принять участие в торгах на любой из них
                </p>
            </div>
            <div class="col-lg-3">
                <div class="agent__icon">
                    <i class="elegent-icon-profile text-primary"></i>
                </div>
                <p class="agent__header">
                    Подача жалоб в УФАС
                </p>
                <p class="agent__text">
                    Если Вашу заявку отклонят неправомерно, агент составит и подаст жалобу в УФАС и защитит Ваши интересы
                </p>
            </div>
            <div class="col-lg-3">
                <div class="agent__icon">
                    <i class="elegent-icon-profile text-primary"></i>
                </div>
                <p class="agent__header">
                    Невысокая стоимость
                </p>
                <p class="agent__text">
                    В банкротных торгах наше участие ненамного дороже ЭЦП, которая Вам не понадобится. Также Вам не нужно проходить аккредитацию на ЭТП и участвовать в аукционе – мы примем участие за Вас.
                </p>
            </div>

        </div>

        <!-- Ожидает на торгах -->

            <h2 class="text-center">Что Вас ждет на торгах за имущество</h2>

        <div class="row agent__step">

            <div class="col-lg-4">

                <div class="agent__step__img">
                    <img src="\img\services\flame-sign-up.png" alt="Image">
                </div>

            </div>

            <div class="col-lg-8">

                <p class="agent__step__header">
                    Подготовка документов
                </p>

                <p>
                    Вам необходимо внимательно подготовить полный пакет документов и правильно сделать их сканы.
                </p>

                <p class="agent__step__mark">
                    Сканы документов могут отклонить из-за любой мелочи (тон печати, изгибы, видимость символов).
                </p>

            </div>
        </div>
        
        <div class="row agent__step">

            <div class="col-lg-8">

                <p class="agent__step__header">
                    Покупка цифровой подписи (ЭЦП)
                </p>

                <p>
                    Вам нужно получить электронную цифровую подпись, это трудный и долгий процесс. Кроме того, есть разные виды ЭЦП, и Вы рискуете приобрести неподходящую.
                </p>

                <p class="agent__step__mark">
                    Стоимость ЭЦП может доходить до 14 000 РУБ.
                </p>

            </div>

            <div class="col-lg-4">

                <div class="agent__step__img">
                    <img src="\img\services\flame-6.png" alt="Image">
                </div>
                
            </div>
            
        </div>


        <div class="row agent__step">

            <div class="col-lg-4">

                <div class="agent__step__img">
                    <img src="\img\services\flame-8.png" alt="Image">
                </div>

            </div>

            <div class="col-lg-8">

                <p class="agent__step__header">
                    Изучение интерфейса площадки и особенностей работы на ней
                </p>

                <p>
                    Все без исключений электронные торговые площадки имеют сложный интерфейс. На его изучение может уйти много времени.
                </p>

                <p class="agent__step__mark">
                    Были прецеденты, когда участник не мог подать заявку 3 дня.
                </p>

            </div>
        </div>
        
        
        <div class="row agent__step">

            <div class="col-lg-8">

                <p class="agent__step__header">
                    Аккредитация на площадке
                </p>

                <p>
                    Аккредитация на площадке занимает до 3 рабочих дней - если всё сделать правильно. При этом необходимо будет подготовить правильные сканы. Проверять придется все вплоть до каждого изгиба и тона печати, чтобы исключить ошибки.
                </p>

                <p class="agent__step__mark">
                    Аккредитация может занять от 3 до 9 рабочих дней.
                </p>

            </div>

            <div class="col-lg-4">

                <div class="agent__step__img">
                    <img src="\img\services\flame-2.png" alt="Image">
                </div>
                
            </div>
            
        </div>

    </div>

    <!-- <div class="container">
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
    </div> -->
    
</section>


<!-- <section class="page-wrapper pt-50 pb-50">
    <?=FaqFormAskQuestion::widget()?>
</section> -->
