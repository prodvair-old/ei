<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $lot common\models\Query\Lot\Lots */
/* @var $links array */
?>
Добрый день, <?= $user->getFullName() ?>,

К лоту <?= $lot->title ?>

По адресу
 
<?= $links['view'] ?>

добавлен новый отчет.

Вы получили это письмо, так-как данный лот у Вас в избранном. 
Вы можете отписаться от уведомлений по данному лоту перейдя по ссылке <?= $links['unsubscribe'] ?>
или 
полностью очистить список избранных лотов воспользовавшись ссылкой <?= $links['unsubscribeAll'] ?>.
