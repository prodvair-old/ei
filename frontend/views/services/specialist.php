<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Консультация специалиста';
$this->params['breadcrumbs'][] = $this->title;
?>

<section>
  <div class="container">
    <h1 class="pt-30"><?= Html::encode($this->title) ?></h1>
    <div class="row pb-50">
      <div class="col-lg-8">
        <p class="h5 specialist_light">
          Получайте своевременную консультацию по ведению торгов на нашей площадке. Наши специалисты имеют большой стаж ведения торгов и всегда смогут Вам помочь.
        </p>

        <h4 class="mt-60">
          Не знаете как и с чего начинать при выборе лота?
        </h4>
        <p>

          Мы всегда подскажем и расскажем как правильно вести торги и оценивать стоимость имущества.
          Узнать всё о лоте бывает не так просто, но с нашими специалистами вы найдёте все недостающие детали и соберете картину полной стоимости объекта торгов.

        </p>

        <h4>
          Что делать если Вы хотите более подробную оценку лота не вникая в процесс оценки?
        </h4>
        <p>
          Наш специалист проведет подробную оценку по лоту и проконсультирует Вас при покупке.
          После рядя оценок, Вам предоставят отчёт об имуществе которое вы хотите преобрести.
        </p>
      </div>
    </div>
    <hr>

    <div class="row pt-90 justify-content-center align-items-center">

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

      <div class="col-lg-3 text-center">
        <!-- <img src="" class="specialist_img" alt="Оценка"> -->
        <p class="specialist_icon"><i class="fas fa-crosshairs"></i></p>

        <h3>
          Оценка
        </h3>
        <p class="specialist_text">
          Закажите оценку у специалиста и он в кротчайшие сроки начнёт работу
        </p>
      </div>

      <div class="col-lg-3 text-center">
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

</section>