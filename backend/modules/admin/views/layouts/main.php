<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use backend\models\UserAccess;

backend\assets\AppAsset::register($this);
dmstr\web\AdminLteAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini skin-blue-light">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render('header') ?>

        <div class="wrapper row-offcanvas row-offcanvas-left">

            <?= $this->render('left') ?>

            <?= $this->render('content', ['content' => $content]) ?>

        </div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
