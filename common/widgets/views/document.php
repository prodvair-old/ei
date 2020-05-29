<?php
/* @var $this yii\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $title string */

?>
<div class="tab-pane active" id="timeline">
    <ul class="timeline timeline-inverse">
        <li class="time-label">
            <span class="bg-red">
                <?= $title ?>
            </span>
        </li>
        <?php foreach ($model->documents as $document):
            $icon = isset(Yii::$app->partams['icons']['file'][$document->ext])
                ? Yii::$app->params['icons']['file'][$document->ext]
                : Yii::$app->params['icons']['file']['default'];
        ?>
            <li>
                <?= $icon ?>
                <div class="timeline-item">
                    <a href="<?= $document->url ?>" class="btn" target="_blank" download><?= $document->name ?></a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
