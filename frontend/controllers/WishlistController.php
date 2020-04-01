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
        try {
            $form = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $user = $form->user;
        $user->removePasswordResetToken();
        if ($lot_id) {
            // отписаться от лота
            if ($model = WhishList::findOne(['user_id' => $user->id, 'lot_id' => $lot_id]))
                $model->delete();
            else
                throw new NotFoundHttpException();
        } else {
            // отписаться от всех лотов
            WhishList::deleteAll(['user_id' => $user->id]);
        }
    }
}
