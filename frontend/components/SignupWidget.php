<?php
namespace frontend\components;

use Yii;
use yii\base\Widget;
use frontend\models\SignupForm;

class SignupWidget extends Widget
{
    public function run(){
        if (!Yii::$app->user->isGuest) {
            return false;
        }
        $model = new SignupForm();

        return $this->render('signup',[
            'model' => $model,
        ]);
    }
}