<?php
namespace frontend\modules\components;

use frontend\modules\models\OrderForm;
use Yii;
use yii\base\Widget;

class ServiceLotFormWidget extends Widget
{
    public $lot;
    public $lotType;

    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            $model = new OrderForm();

            return $this->render('serviceLotForm', [
                'model'   => $model,
                'lot'     => $this->lot,
                'lotType' => $this->lotType,
            ]);
        } else {
            return false;
        }
    }
}