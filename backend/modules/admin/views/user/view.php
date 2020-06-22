<?php
/* @var $this yii\web\View */
/* @var $model common\models\db\User */

$this->title = Yii::t('app', 'View') . ' ' . Yii::t('app', 'user');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->fullName;

?>
<div class='row'>
    <div class="col-md-3">
        <div class="box box-primary">
            <?= $this->render('_view_profile', [
                'model' => $model,
            ]) ?>
        </div>
        <div class="box box-primary">
            <?= $this->render('_view_about_me', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>
                <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="activity">
                    <div class="post">
                        <div class="user-block">
                            Список избранных лотов, список отчетов и прочего.
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="timeline">
                    <ul class="timeline timeline-inverse">
                        <li class="time-label">
                            <span class="bg-red">
                                19.06.2020
                            </span>
                        </li>
                        <li>
                            <i class="fa fa-pencil bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> 18:42</span>
                                <h3 class="timeline-header"><a href="#">Редактирование</a> Лот #334</h3>
                                <div class="timeline-body">
                                    Краткое название Лота.
                                </div>
                                <div class="timeline-footer">
                                    <a class="btn btn-primary btn-xs">Перейти к лоту</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<div>
