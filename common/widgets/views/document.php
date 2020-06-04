<?php
/* @var $this yii\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $title string */

$icons = Yii::$app->params['icons']['file'];
?>
<div>
    <h4><?= $title ?></h4>
    <ul>
        <?php foreach($model->documents as $document): ?>
            <li>
                <?= isset($icons[$document->ext]) ? $icons[$document->ext] : $icons['default'] ?>
                <a href="<?= $document->url ?>" class="btn" target="_blank" download><?= $document->name ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
