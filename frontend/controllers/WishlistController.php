<?php
namespace frontend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use frontend\models\ResetPasswordForm;
use common\models\User;
use common\models\Query\WishList;


/**
 * Wishlist controller
 */
class WishlistController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Unsubscribe User.
     *
     * @param string $token
     * @param integer|false $lot_id if false then usubscribe from all lots 
     * @return mixed
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionUnsubscribe($token, $lot_id = false)
    {
        // проверить существование токена
        try {
            $form = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        // юзер найден по токену в $form
        $user = $form->user;
        
        // удалить использованный токен
        $user->removePasswordResetToken();
        $user->save();
        
        // задать условие удаления одной или всех подписок
        $condition = $lot_id
            ? ['userId' => $user->id, 'lotId' => $lot_id]
            : ['userId' => $user->id];
        $models = WishList::find()->where($condition)->all();
        
        // удалить подписки
        foreach ($models as $model) {
            $model->delete();
        }
        
        // перейти на главную страницу и известить юзера о том, что он успешно отписался
        Yii::$app->session->setFlash('success', 
            $user->getFullName() . ', Вы успешно отписаны от выбранных лотов.');
        return $this->goHome();
    }
}
