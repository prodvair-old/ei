<?php
namespace frontend\components;

use Yii;
use yii\base\Widget;
use frontend\models\PasswordResetRequestForm;

class ResetPasswordWidget extends Widget
{
    public function run(){
        if (!Yii::$app->user->isGuest) {
            return false;
        }
        $model = new PasswordResetRequestForm();

        return $this->render('resetPassword',[
            'model' => $model,
        ]);
    }
}