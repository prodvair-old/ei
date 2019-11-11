<?php
namespace frontend\components;

use Yii;
use yii\base\Widget;

use frontend\models\ServiceLotForm;

class ServiceLotFormWidget extends Widget
{
    public $lotId;
    public $lotType;

    public function run(){
        if (!Yii::$app->user->isGuest) {
            $model = new ServiceLotForm();

            return $this->render('serviceLotForm',[
                'model' => $model,
                'lotId' => $this->lotId,
                'lotType' => $this->lotType,
            ]);
        } else {
            return false;
        }
    }
}