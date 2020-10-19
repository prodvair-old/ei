<?php

namespace frontend\modules\profile\controllers;

use common\models\db\Notification;
use common\models\db\Purchase;
use common\models\db\Report;
use common\models\db\SearchQueries;
use common\models\db\Subscription;
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
use frontend\modules\profile\models\UserSetting;
use common\models\SendSMS;

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
                'class'        => AccessControl::className(),
                'rules'        => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    return $this->goHome();
                }
            ],
        ];
    }

    public function actionWishList()
    {
        $wishCount = WishList::find()->where(['user_id' => Yii::$app->user->id])->count();
        $pages = new Pagination(['totalCount' => $wishCount, 'pageSize' => 6]);

        $wishList = WishList::find()->where(['user_id' => Yii::$app->user->id])->offset($pages->offset)->limit($pages->limit)->orderBy('id DESC')->all();

        return $this->render('wishList', ['wishCount' => $wishCount, 'pages' => $pages, 'wishList' => $wishList]);
    }

    /**
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionSetting()
    {
        $success = null;
        $error = null;

        $form = new ProfileForm();
        $form->user_id = Yii::$app->user->id;

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
    }

    public function actionSetting_image()
    {
        if (!Yii::$app->user->isGuest) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = new UserSetting();

            $form = new ProfileForm();
            $form->user_id = Yii::$app->user->id;

            if ($model->load(Yii::$app->request->post())) {
                $model->photo = UploadedFile::getInstance($model, 'photo');
                $model->passport = UploadedFile::getInstance($model, 'passport');

                return $model->upload(Yii::$app->user->id);
            }

            return false;
        } else {
            return $this->goHome();
        }
    }

    public function actionEditPhone()
    {
        if (Yii::$app->request->isAjax) {
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

    public function actionPurchase()
    {
        $purchaseIdList = Purchase::getPurchasedReportsIdByUser(Yii::$app->user->getId());

        $purchasedReports = Report::find()
            ->where(['in', 'id', $purchaseIdList])
            ->all();

        return $this->render('purchase', [
            'model' => $purchasedReports,
        ]);
    }

    public function actionSubscription()
    {
        $subList = Subscription::find()
            ->innerJoinWith('invoice', true)
            ->where(['subscription.user_id' => Yii::$app->user->getId()])
            ->andWhere(['=', 'invoice.paid', true])
            ->andWhere(['>', 'till_at', time()])
            ->one();

        return $this->render('subscription', [
            'model' => $subList,
        ]);
    }

    public function actionSearchPreset()
    {

        $searchQueriesCount = SearchQueries::find()->where(['user_id' => Yii::$app->user->id])->count();
        $pages = new Pagination(['totalCount' => $searchQueriesCount, 'pageSize' => 15]);

        $searchQueries = SearchQueries::find()->where(['user_id' => Yii::$app->user->id])->offset($pages->offset)->limit($pages->limit)->orderBy('id DESC')->all();

        return $this->render('searchPreset', ['searchQueriesCount' => $searchQueriesCount, 'pages' => $pages, 'searchQueries' => $searchQueries]);
    }

    public function actionSearchPresetChange()
    {
        $searchQueries = SearchQueries::findOne(['id' => Yii::$app->request->queryParams['id']]);

        $searchQueries->send_email = (Yii::$app->request->queryParams['send_email'] === 'true') ? true : false;

        return $searchQueries->update();
    }

    public function actionSearchPresetDel()
    {
        $searchQueries = SearchQueries::findOne(['id' => Yii::$app->request->queryParams['id']]);

        return $searchQueries->delete();
    }

    public function actionGetCode()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $code = rand(1000, 9999);

        $post = Yii::$app->request->post();
        $session = Yii::$app->session;
        $session->set('userCode', $code);
        $session->set('userPhone', $post['UserEditPhone']['phone']);

        $model = new SendSMS();

        $model->phone = preg_replace('/[^0-9]/', '', $post['UserEditPhone']['phone']);
        $model->message = "Vash kod: $code";

        $result = false;
        $mess = 'Ошибка сервера';

        if ($model->check()) {
            if ($response = $model->send()) {
                if ($response['status']) {
                    $result = true;
                }
                $mess = $response['text'];
            }
        }

        return ['result' => $result, 'mess' => $mess];
    }
}
