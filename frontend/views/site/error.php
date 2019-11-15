<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<section class="error">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="error__header">Ошибка 404</h1>
                <p class="error__text">страница не найдена</p>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 text-center col-sm-6 col-xs-12">
                <a href="/" class="error__btn">Вернуться на главную</a>
            </div>
        </div>
    </div>
</section>
<div class="site-error">


    

</div>
