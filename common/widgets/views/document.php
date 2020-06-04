<?php
/* @var $this yii\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $title string */

$icons = Yii::$app->params['icons']['file'];

?>
<div class="tab-pane active" id="timeline">
    <ul class="timeline timeline-inverse">
        <li class="time-label">
            <span class="bg-red">
                <?= $title ?>
            </span>
        </li>
        <?php foreach ($model->documents as $document): ?>
            <li>
                <?= isset($icons[$document->ext]) ? $icons[$document->ext] : $icons['default'] ?>
                <div class="timeline-item">
                    <a href="<?= $document->url ?>" class="btn" target="_blank" download title="<?= $document->name ?>">
                        <?= $document->shortPart ?>
                    </a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

