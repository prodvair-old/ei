<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;



// Запросы
use common\models\Query\MetaDate;
use common\models\Query\Lot\Lots;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['post'],
                    // 'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $metaData = MetaDate::find()->where(['mdName' => 'index'])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;

        // $lotsBankruptCount = Lots::isActive()->joinWith(['categorys', 'torg', 'thisPriceHistorys'])->where(['torg.typeId' => 1])->count();
        // $lotsArrestCount = Lots::isActive()->joinWith(['categorys', 'torg', 'thisPriceHistorys'])->where(['torg.typeId' => 2])->count();
        // $lotsZalogCount = Lots::isActive()->joinWith(['categorys', 'torg', 'thisPriceHistorys'])->where(['torg.typeId' => 3])->count();
        // [
        //     'lotsBankruptCount' => $lotsBankruptCount,
        //     'lotsArrestCount' => $lotsArrestCount,
        //     'lotsZalogCount' => $lotsZalogCount
        // ]
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->request->isPost) {
                return ['result'=>false, 'error'=>'Уже авторизованы'];
            } else {
                return $this->goHome();
            }
            
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return ['result'=>true];
        } else {
            $model->password = '';
            return ['result'=>false, 'error'=>'Неверный логин или пароль'];
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Спасибо за регистрацию. Пожалуйста перейдите на указанную почту, для подтверждения акаунта.');
            return ['result'=>true, 'error'=>'Спасибо за регистрацию! Подтвердите почту.'];
        }
        Yii::$app->session->setFlash('error', 'Такой пользователь уже существует');
        return ['result'=>false, 'error'=>'Такой пользователь уже существует'];
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Вам на почту отправлена ссылка для восстановления!');
                return ['result'=>true, 'error'=>'Вам на почту отправлена ссылка для восстановления!'];
            } else {
                Yii::$app->session->setFlash('error', 'Извините, мы не можем восстановить пароль. Такого e-mail не существует.');
                return ['result'=>false, 'error'=>'Извините, мы не можем восстановить пароль. Такого e-mail не существует.'];
            }
        }
        return ['result'=>false, 'error'=>'Заполните поля'];
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Ваш E-mail подтверждён!');
                return $this->render('verifyEmail');
            }
        }

        Yii::$app->session->setFlash('error', 'Извините но ваш токен не действителен.');
        return $this->goHome();
    }
    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
