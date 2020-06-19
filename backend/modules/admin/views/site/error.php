<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<!-- Main content -->
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>

            <p>
                <?= nl2br(Html::encode($message)) ?>
            </p>

            <p>
                <?= Yii::t('app', 'Sorry, but an error occurred. If you believe that the error is significant, please let us know by email : {email}', [
                    'email' => Yii::$app->params['email']['support'],
                ]) ?>
            </p>

        </div>
    </div>

</section>
