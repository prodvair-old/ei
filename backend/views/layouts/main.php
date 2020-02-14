<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
if (Yii::$app->controller->action->id === 'login') {
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    switch (Yii::$app->user->identity->role) {
        case 'agent':
            Yii::$app->params['role'] = 'Агент';
            break;
        case 'arbitr':
            Yii::$app->params['role'] = 'Арбитражный управляющи';
            break;
        case 'sro':
            Yii::$app->params['role'] = 'СРО';
            break;
        case 'etp':
            Yii::$app->params['role'] = 'Торговая площадка';
            break;
        case 'manager':
            Yii::$app->params['role'] = 'Менеджер';
            break;
        case 'admin':
            Yii::$app->params['role'] = 'Администратор';
            break;
        case 'superAdmin':
            Yii::$app->params['role'] = 'Главный администратор';
            break;
    }

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@bower/admin-lte/dist');
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


        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <div class="wrapper row-offcanvas row-offcanvas-left">

            <?= $this->render(
                'left.php',
                ['directoryAsset' => $directoryAsset]
            )
            ?>

            <?= $this->render(
                'content.php',
                ['content' => $content, 'directoryAsset' => $directoryAsset]
            ) ?>

        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
