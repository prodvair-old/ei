<?php

namespace frontend\modules\profile\controllers;

use common\models\db\Notification;
use common\models\db\SearchQueries;
use common\models\db\WishList;
use frontend\modules\profile\components\NotificationService;
use frontend\modules\profile\forms\NotificationForm;
use frontend\models\UserEditPhone;
use frontend\modules\profile\components\ProfileService;
use frontend\modules\profile\forms\ProfileForm;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use frontend\models\UserSetting;

class ProfileController extends Controller
{

    /**
     * @var ProfileService
     */
    protected $profileService;

    protected $notificationService;

    public function __construct($id, $module, ProfileService $profileService, NotificationService $notificationService, $config = [])
    {
        $this->profileService = $profileService;
        $this->notificationService = $notificationService;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['notification'],
                'rules' => [
                    [
                        'actions' => ['notification'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionWishList()
    {
        if (!Yii::$app->user->isGuest) {

            $wishCount = WishList::find()->where(['user_id' => Yii::$app->user->id])->count();
            $pages = new Pagination(['totalCount' => $wishCount, 'pageSize' => 6]);

            $wishList = WishList::find()->where(['user_id' => Yii::$app->user->id])->offset($pages->offset)->limit($pages->limit)->orderBy('id DESC')->all();

            return $this->render('wishList', ['wishCount' => $wishCount, 'pages' => $pages, 'wishList' => $wishList]);
        } else {
            return $this->goHome();
        }
    }

    /**
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionSetting()
    {
        if (!Yii::$app->user->isGuest) {

            $success = null;
            $error = null;

            $form = new ProfileForm();
            $readModel = $this->profileService->findProfile(Yii::$app->user->identity->getId())->asArray()->one();
            if ($readModel) {
                $form->setAttributes($readModel);
            }

            $model_image = new UserSetting();
            $model_phone = new UserEditPhone();

            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                $form->load($post);
                if ($form->validate() && $this->profileService->save($form)) {
                    $success = 'Настройки успешно сохранены';
                } else {
                    $error = 'Ошибка при сохранении настроек!';
                }
            }

            return $this->render('setting', [
                'model'       => $form,
                'success'     => $success,
                'error'       => $error,
                'model_image' => $model_image,
                'model_phone' => $model_phone
            ]);
        } else {
            return $this->goHome();
        }
    }

    public function actionEditPhone()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $result = false;
            $mess = 'Серверная ошибка';
            $session = Yii::$app->session;

            if ($session->has('userCode')) {
                $model = new UserEditPhone();

                if ($model->load(Yii::$app->request->post())) {
                    if (str_replace('-', '', $model->code) == $session->get('userCode')) {
                        if ($this->profileService->savePhone($session->get('userPhone'))) {
                            $result = true;
                            $mess = 'Успешно';
                            $session->remove('userCode');
                            $session->remove('userPhone');
                        }
                    } else {
                        $mess = "Не верный код";
                    }
                }
            } else {
                $mess = "Время истекло";
            }
            return ['result' => $result, 'error' => $mess];
        } else {
            return $this->goHome();
        }
    }

    /**
     * @return string|Response
     */
    public function actionNotification()
    {

        $model = Notification::findOne(['user_id' => Yii::$app->user->identity->getId()]);

        if (!$model) {
            $model = new Notification();
        }

        $formModel = new NotificationForm();
        $formModel->setAttributes($model->getAttributes());

        if (Yii::$app->request->isPost) {
            $formModel->load(Yii::$app->request->post());
            $formModel->user_id = Yii::$app->user->identity->getId();

            if ($this->notificationService->save($formModel, $model)) {
                Yii::$app->session->setFlash('success', 'Уведомления успешно обновлены.');
            }
        }

        return $this->render('notification', [
            'caption'   => 'Уведомления',
            'formModel' => $formModel,
        ]);

    }

    public function actionSearchPreset()
    {
        if (!Yii::$app->user->isGuest) {

            $searchQueriesCount = SearchQueries::find()->where(['user_id' => Yii::$app->user->id])->count();
            $pages = new Pagination(['totalCount' => $searchQueriesCount, 'pageSize' => 15]);

            $searchQueries = SearchQueries::find()->where(['user_id' => Yii::$app->user->id])->offset($pages->offset)->limit($pages->limit)->orderBy('id DESC')->all();

            return $this->render('searchPreset', ['searchQueriesCount' => $searchQueriesCount, 'pages' => $pages, 'searchQueries' => $searchQueries]);
        } else {
            return $this->goHome();
        }
    }

    public function actionSearchPresetChange()
    {
        if (!Yii::$app->user->isGuest) {
            $searchQueries = SearchQueries::findOne(['id' => Yii::$app->request->queryParams[ 'id' ]]);

            $searchQueries->send_email = (Yii::$app->request->queryParams[ 'send_email' ] === 'true') ? true : false;

            return $searchQueries->update();
        } else {
            return $this->goHome();
        }
    }

    public function actionSearchPresetDel()
    {
        if (!Yii::$app->user->isGuest) {
            $searchQueries = SearchQueries::findOne(['id' => Yii::$app->request->queryParams[ 'id' ]]);

            return $searchQueries->delete();
        } else {
            return $this->goHome();
        }
    }

}
