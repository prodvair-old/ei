<?php
namespace frontend\modules\components;

use Yii;
use yii\base\Widget;

use frontend\models\ServiceLotForm;

class ServiceLotFormWidget extends Widget
{
    public $lot;
    public $lotType;

    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            $model = new ServiceLotForm();

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