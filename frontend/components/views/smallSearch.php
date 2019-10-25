<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<div class="search-form-main">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="from-inner">
            
            <div class="row shrink-auto-sm gap-1">
            
                <div class="col-12 col-auto">
                    <div class="col-inner">
                        <div class="row cols-1 cols-sm-3 gap-1">
            
                            <div class="col">
                                <div class="col-inner">
                                    <?=$form->field($model, 'type')->dropDownList(['bankrupt' => 'Банкротное имущество', 'arrest' => 'Арестованное имущество'])->label('Тип лота');?>
                                    <!-- <div class="form-group">
                                        <label>Nbg kjnjd</label>
                                        <select class="chosen-the-basic form-control form-control-sm" placeholder="Select one" tabindex="2">
                                            <option></option>
                                            <option>All</option>
                                            <option>Adventure</option>
                                            <option>City tour</option>
                                            <option>Honeymoon</option>
                                            <option>Cultural</option>
                                        </select>
                                    </div> -->
                                </div>
                            </div>

                            <div class="col">
                                <div class="col-inner">
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <select class="chosen-the-basic form-control form-control-sm" placeholder="Select two" tabindex="2">
                                            <option></option>
                                            <option>All</option>
                                            <option>Asia</option>
                                            <option>Europe</option>
                                            <option>Africa</option>
                                            <option>America</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="col-inner">
                                    <?= $form->field($model, 'search')->textInput(['placeholder'=> 'Найти лот...', 'class'=>'form-control form-readonly-control'])->label('Поиск') ?>
                                    <!-- <div class="form-group">
                                        <label>When</label>
                                        <input type="text" class="form-control form-readonly-control air-datepicker" placeholder="Pick a month" data-min-view="months" data-view="months" data-date-format="MM yyyy" data-language="en" data-auto-close="true" readonly>
                                    </div> -->
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="col-12 col-shrink">
                    <div class="col-inner">
                        <?= Html::submitButton('<i class="ion-android-search"></i>', ['class' => 'btn btn-primary btn-block']) ?>
                        <!-- <a href="#" class="btn btn-primary btn-block"><i class="ion-android-search"></i></a> -->
                    </div>
                </div>
                
            </div>
        </div>
    <?php ActiveForm::end() ?>
</div>