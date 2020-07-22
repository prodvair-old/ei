<?php

/* @var $this yii\web\View */
/* @var $lot array of a Stat data */

?>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('app', 'Visitors Report') ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pad">
                    <!-- Place for Graph -->
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="pad box-pane-right bg-green" style="min-height: 280px">
                    <?php foreach($lot as $name => $var): ?>
                    <div class="description-block margin-bottom">
                        <div class="sparkbar pad" data-color="#fff">
                            <span><i class='fa fa-2x fa-<?= $var['icon'] ?>'></i></span>
                        </div>
                        <h5 class="description-header"><?= $var['value'] ?></h5>
                        <span class="description-text"><?= Yii::t('app', $var['caption']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.box -->
