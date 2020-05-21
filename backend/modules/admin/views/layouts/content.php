<?php
use yii\widgets\Breadcrumbs;
use yiister\adminlte\widgets\FlashAlert;
?>
<div class="content-wrapper">
    <section class="content-header">
        <?= FlashAlert::widget() ?>
        <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
    </section>

    <section class="content">
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Версия</b> 2.0
    </div>
    <strong>Copyright &copy; 2019-2020 <a href="http://ei.ru/">ei.ru</a>.</strong> Все права защищены.
</footer>