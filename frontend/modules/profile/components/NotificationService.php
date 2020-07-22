<?php


namespace frontend\modules\profile\components;

use common\models\db\Notification;
use frontend\modules\profile\forms\NotificationForm;
use Yii;
use yii\base\Component;

class NotificationService extends Component
{

    /**
     * @param NotificationForm $form
     * @param Notification $model
     * @return bool
     */
    public function save(NotificationForm $form, Notification $model)
    {
        $data = $form->getAttributes();
        $model->new_picture = $data[ 'new_picture' ];
        $model->new_report = $data[ 'new_report' ];
        $model->price_reduction = $data[ 'price_reduction' ];
        $model->user_id = Yii::$app->user->identity->getId();

        if ($model->save()) {
            return true;
        }

        return false;
    }
}