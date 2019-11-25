<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\models\SiteMapCreater;

/**
 * Sitemap controller
 */
class SitemapController extends Controller
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
                    'logout' => ['post'],
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        return $this->renderPartial('index',['host' => Yii::$app->request->hostInfo]);
    }

    public function actionPages($type, $limit = null)
    {
        $sitemap = new SiteMapCreater();

        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        // Если в кэше нет карты сайта        
        if (!$xml_sitemap = Yii::$app->cache->get("sitemap-pages-$type-$limit-1")) {
            //Получаем мыссив всех ссылок
            $urls = $sitemap->getUrl([
                'type' => $type,
                'limit' => $limit
            ]);

            $xml_sitemap = $this->renderPartial('sitemap', [
                'host' => Yii::$app->request->hostInfo,
                'urls' => $urls,
            ]);
            Yii::$app->cache->set("sitemap-pages-$type-$limit", $xml_sitemap, 3600*24); 
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        return $xml_sitemap;
    }

    public function actionLotsfilter()
    {
        $sitemap = new Sitemap();

        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        
        // Если в кэше нет карты сайта        
        if (!$xml_sitemap = Yii::$app->cache->get('sitemap_lots-filter1')) {
        //     //Получаем мыссив всех ссылок
            $urls = $sitemap->getUrl([
                'type' => 'lots-filter',
            ]);


            $xml_sitemap = $this->renderPartial('sitemap', [
                'host' => Yii::$app->request->hostInfo,
                'urls' => $urls,
            ]);
            
            Yii::$app->cache->set('sitemap_lots-filter', $xml_sitemap, 3600*24*7);
        } 

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        return $xml_sitemap;
    }
    public function actionLotsarrestfilter()
    {
        $sitemap = new Sitemap();

        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        
        // Если в кэше нет карты сайта        
        if (!$xml_sitemap = Yii::$app->cache->get('sitemap_lots-arrest-filter')) {
        //     //Получаем мыссив всех ссылок
            $urls = $sitemap->getUrl([
                'type' => 'lots-arrest-filter',
            ]);


            $xml_sitemap = $this->renderPartial('sitemap', [
                'host' => Yii::$app->request->hostInfo,
                'urls' => $urls,
            ]);
            
            Yii::$app->cache->set('sitemap_lots-arrest-filter', $xml_sitemap, 3600*24*7);
        } 

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        return $xml_sitemap;
    }
    public function actionLotsarrest($limit)
    {
        $sitemap = new Sitemap();

        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        
        // Если в кэше нет карты сайта        
        if (!$xml_sitemap = Yii::$app->cache->get('sitemap_lots-arrest-'.$limit)) {
            //Получаем мыссив всех ссылок
            $urls = $sitemap->getUrl([
                'type' => 'lots-arrest',
                'limit' => $limit
            ]);


            $xml_sitemap = $this->renderPartial('sitemap', [
                'host' => Yii::$app->request->hostInfo,
                'urls' => $urls,
            ]);
            
            Yii::$app->cache->set('sitemap_lots-arrest-'.$limit, $xml_sitemap, 3600*24*7);
        } 

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        return $xml_sitemap;
    }
    public function actionLots($category_lot)
    {
        $sitemap = new Sitemap();

        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        
        // Если в кэше нет карты сайта        
        if (!$xml_sitemap = Yii::$app->cache->get('sitemap_lots-'.$category_lot)) {
        //     //Получаем мыссив всех ссылок
            $urls = $sitemap->getUrl([
                'type' => 'lots',
                'category' => $category_lot
            ]);


            $xml_sitemap = $this->renderPartial('sitemap', [
                'host' => Yii::$app->request->hostInfo,
                'urls' => $urls,
            ]);
            
            Yii::$app->cache->set('sitemap_lots-'.$category_lot, $xml_sitemap, 3600*24*7);
        } 

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        return $xml_sitemap;
    }
    public function actionArbtr($is_have)
    {
        $sitemap = new Sitemap();

        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        // Если в кэше нет карты сайта        
        if (!$xml_sitemap = Yii::$app->cache->get('sitemap_arbtr-'.$is_have)) {

            if ($is_have == 'is_have_lot') {
                $have = true;
            } else {
                $have = false;
            }
                
            $urls = $sitemap->getUrl([
                'type' => 'arbtr',
                'have' => $have
            ]);

            $xml_sitemap = $this->renderPartial('sitemap', [
                'host' => Yii::$app->request->hostInfo,
                'urls' => $urls,
            ]);
            
            Yii::$app->cache->set('sitemap_arbtr-'.$is_have, $xml_sitemap, 3600*24*7);
        } 

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        return $xml_sitemap;
    }
    public function actionBnkr($is_type, $limit)
    {
        
        $sitemap = new Sitemap();

        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        // Если в кэше нет карты сайта        
        if (!$xml_sitemap = Yii::$app->cache->get('sitemap_bnkr12-'.$is_type)) {
                
            $urls = $sitemap->getUrl([
                'type' => 'bnkr',
                'is_type' => $is_type,
                'limit' => $limit
            ]);
            $xml_sitemap = $this->renderPartial('sitemap', [
                'host' => Yii::$app->request->hostInfo,
                'urls' => $urls,
            ]);
            Yii::$app->cache->set('sitemap_bnkr-'.$is_type, $xml_sitemap, 3600*24*7);
        } 

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        return $xml_sitemap;
    }

}
