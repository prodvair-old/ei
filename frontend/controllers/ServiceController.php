<?php
// namespace frontend\controllers;

// use Yii;
// use yii\web\Controller;
// use yii\filters\VerbFilter;
// use yii\filters\AccessControl;

// /**
//  * Pages controller
//  */
// class ServiceController extends Controller
// {
//     /**
//      * {@inheritdoc}
//      */
//     public function behaviors()
//     {
//         return [
//             'access' => [
//                 'class' => AccessControl::className(),
//                 'only' => ['logout', 'signup'],
//                 'rules' => [
//                     [
//                         'actions' => ['signup'],
//                         'allow' => true,
//                         'roles' => ['?'],
//                     ],
//                     [
//                         'actions' => ['logout'],
//                         'allow' => true,
//                         'roles' => ['@'],
//                     ],
//                 ],
//             ],
//             'verbs' => [
//                 'class' => VerbFilter::className(),
//                 'actions' => [
//                     'logout' => ['post'],
//                 ],
//             ],
//         ];
//     }
//     /**
//      * {@inheritdoc}
//      */
//     public function actions()
//     {
//         return [
//             'error' => [
//                 'class' => 'yii\web\ErrorAction',
//             ],
//             'captcha' => [
//                 'class' => 'yii\captcha\CaptchaAction',
//                 'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//             ],
//         ];
//     }

//     /**
//      * Displays homepage.
//      *
//      * @return mixed
//      */
//     public function actionAbout()
//     {
//         return $this->render('about');
//     }
//     public function actionLicens()
//     {
//         return $this->render('licens');
//     }
//     public function actionPolitic()
//     {
//         return $this->render('politic');
//     }
//     public function actionContact()
//     {
//         return $this->render('contact');
//     }
//     public function actionService()
//     {
//         return $this->render('service');
//     }
//     public function actionFaq()
//     {
//         return $this->render('faq');
//     }
//     public function actionSitemap()
//     {
//         return $this->render('sitemap');
//     }
//     public function actionServiceAgent()
//     {
//         return $this->render('serviceAgent');
//     }


// }
