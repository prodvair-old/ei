<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

use common\models\User;
use frontend\models\user\NotificationForm;

/**
 * User Profile controller
 */
class ProfileController extends Controller
{
	public $_model = null;
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['notification'],
                'rules' => [
                    [
                        'actions' => ['notification'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Edit User Notifications.
     *
     * @param integer $id User ID
     * @return mixed
     */
    public function actionNotification($id)
    {
        $user = $this->findModel($id);
        $model = new NotificationForm([
            'new_picture'     => $user->new_picture,
            'new_report'      => $user->new_report,
            'price_reduction' => $user->price_reduction,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $user->new_picture     = $model->new_picture;
            $user->new_report      = $model->new_report;
            $user->price_reduction = $model->price_reduction;
            $user->save(false);
            Yii::$app->session->setFlash('success', 'Уведомления успешно обновлены');
            
            return $this->refresh();
        } else {
            return $this->render('update', [
                'caption' => 'Уведомления',
                'form'    => '_notification_form',
                'model'   => $model,
            ]);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id User ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
		if($this->_model === null) 
		{
			if(($this->_model = User::findOne($id)) && $this->_model->status == User::STATUS_ACTIVE) 
			{
				return $this->_model;
			} else {
				throw new NotFoundHttpException('The requested model does not exist.');
			}
		}
	}
}
