<?php

/* @var $this yii\web\View */
/* @var $recently_updated common\models\db\Lot */

?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('app', 'Recently Updated Lots') ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul class="products-list product-list-in-box">
            <?php foreach($recently_updated as $lot): ?>
            <li class="item">
                <div class="product-img">
                    <img src="<?= $lot->getImage('thumb') ?>" alt="<?= $lot->getFileDescription() ?>">
                </div>
                <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">
                        <?= $lot->shortTitle ?>
                        <span class="label label-warning pull-right"><?= $lot->startPrice ?></span>
                    </a>
                    <span class="product-description">
                        Samsung 32" 1080p 60Hz LED Smart HDTV.
                    </span>
                </div>
            </li>
            <!-- /.item -->
            <?php endforeach; ?>
        </ul>
    </div>
    <!-- /.box-body -->
</div>
