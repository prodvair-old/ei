<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\faq\FaqFormAskQuestion;

$this->title = 'Консультация специалиста';
$this->params['breadcrumbs'][] = $this->title;
?>

<section>
    <div class="container">
        <h1 class="pt-30  text-center"><?= Html::encode($this->title) ?></h1>
        <div class="row pb-50">
            <div class="col-lg-10 offset-lg-1">
                <p class="text-center h4 specialist_light">
                    Получайте своевременную консультацию по ведению торгов на нашей площадке. Наши специалисты имеют большой стаж ведения торгов и всегда смогут Вам помочь.
                </p>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-lg-12">
                <h4 class="mb-0">    
                    Не знаете как и с чего начинать при выборе лота? 
                </h4>
                <p class="blockquote">
                    Мы всегда подскажем и расскажем как правильно вести торги и оценивать стоимость имущества.
                    Узнать всё о лоте бывает не так просто, но с нашими специалистами вы найдёте все недостающие детали и соберете картину полной стоимости объекта торгов.
                </p>
            </div>
        </div>

        <div class="row pt-50">
            <div class="col-lg-12">
                <h4 class="mb-0">
                    Что делать если Вы хотите более подробную оценку лота не вникая в процесс оценки?
                </h4>
                <p class="blockquote"> 
                    Наш специалист проведет подробную оценку по лоту и проконсультирует Вас при покупке. 
                    После рядя оценок, Вам предоставят отчёт об имуществе которое вы хотите преобрести.
                </p>
            </div>
        </div>

        <div class="row pt-90 pb-90">

            <div class="col-lg-4 text-center">
                <!-- <img src="" class="specialist_img" alt="Выбор"> -->
                <p class="specialist_icon"><i class="fas fa-star specialist_icon"></i></p>
                <h3>
                    Выбор
                </h3>
                <p class="specialist_text">
                    Выберите лот по которому хотите получить более подробную оценку
                </p>
            </div>

            <div class="col-lg-4 text-center">
                <!-- <img src="" class="specialist_img" alt="Оценка"> -->
                <p class="specialist_icon"><i class="fas fa-crosshairs"></i></p>

                <h3>
                    Оценка
                </h3>
                <p class="specialist_text">
                    Закажите оценку у специалиста и он в кротчайшие сроки начнёт работу
                </p>
            </div>

            <div class="col-lg-4 text-center">
                <!-- <img src="" class="specialist_img" alt="Отчёт"> -->
                <p class="specialist_icon"><i class="fas fa-clipboard-list"></i></p>
                <h3>
                    Отчёт
                </h3>
                <p class="specialist_text">
                    После проведения оценки специалист предоставит Вам отчёт о стоимости имущества
                </p>
            </div>
            
        </div>

    </div>

    <div class="container-fluid">
    
        <div class="row text-center feedback">
            <form class="col-lg-12">
                <h2 class="feedback__header">
                    Остались вопросы?
                </h2>
                <p class="feedback__text">
                    Чтобы получить более подробную консультацию по нашим услугам, оставьте свои данные и мы свяжемся с Вами!
                </p>
                <p class="feedback__form">
                    <input type="text" placeholder="Имя" required>
                    <input type="text" placeholder="Телефон" required>
                    <!-- <input type="text" placeholder="Email"> -->
                    <button type="submit" value="Отправить">Отправить</button>
                </p>
                <label for="chkb-1" class="feedback__policy">
                    <input type="checkbox" id="chkb-1" class="feedback__policy__checkbox" required>
                    <div id="chkb-1">Я согласен с условиями <a href="/policy" target="_blank">политики конфиденциальности</a></div>
                </label>
            </form>
        </div>

    </div>
       
    
</section>
