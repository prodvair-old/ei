<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\data\Pagination;
use moonland\phpexcel\Excel;

use backend\models\UserAccess;
use backend\models\Editors\LotEditor;
use backend\models\Editors\TorgEditor;
use backend\models\ImportFIleForm;

use common\models\Query\Arrest\LotsArrest;
use backend\models\HistoryAdd;

/**
 * Find controller
 */
class FindController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['arrest', 'update', 'create', 'index', 'image-del'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionArrest()
    {
        if (!UserAccess::forAgent()) {
            return $this->goHome();
        }
        $modelImport = new ImportFIleForm();

        if(Yii::$app->request->post()){
            ini_set('memory_limit', '1024M');
            $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport,'fileImport');
            
            if($modelImport->fileImport && $modelImport->validate()){
                $where = $modelImport->excelArrest();

                $lots = LotsArrest::find()->where($where)->all();

                if ($lots[0] != null) {
                    HistoryAdd::export(1, 'find/arrest', 'Иммущество успешно экспортировано', null, Yii::$app->user->identity);
                
                    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

                    Excel::export([
                        'models' => $lots,
                        'columns' => [
                            'lotId:text',
                            [
                                'attribute' => 'lotUrl',
                                'header' => 'Ссылка на лот',
                                'format' => 'text',
                                'value' => function($model) {
                                    return 'https://ei.ru/'.$model->lotUrl;
                                },
                            ],
                            'torgs.trgNotificationUrl:text',
                            'lotPropName:text',
                            'lotTorgReason:text',
                            'torgs.trgBidAuctionDate:datetime',
                            'torgs.trgBidFormName:text',
                            'torgs.trgPublished:datetime',
                            'lotWinnerName:text',
                            'lotContractPrice:text',
                            'lotStartPrice:text',
                            'lotCadastre:text',
                            'lotVin:text',
                            'lotKladrLocationName:text',
                            [
                                'attribute' => 'lotCategory',
                                'header' => 'Категория лота',
                                'format' => 'text',
                                'value' => function($model) {
                                    return $model->lotCategory[0];
                                },
                            ],
                            'lot_archive:boolean',
                        ],
                        'headers' => [
                            'lotId' => 'ID лота',
                            'torgs.trgNotificationUrl' => 'Ссылка на извещение',
                            'lotPropName' => 'Описание',
                            'lotTorgReason' => 'Основания реализации торгов',
                            'torgs.trgBidAuctionDate' => 'Дата и время проведения торгов',
                            'torgs.trgBidFormName' => 'Форма торгов',
                            'torgs.trgPublished' => 'Дата публикации',
                            'lotWinnerName' => 'Победитель',
                            'lotContractPrice' => 'Цена предложенное победителем',
                            'lotCadastre' => 'Кадастровый номер',
                            'lotVin' => 'VIN номер',
                            'lotKladrLocationName' => 'Адрес',
                            'lot_archive' => 'В архиве',
                        ],
                    ]);
                } else {
                    HistoryAdd::export(2, 'find/arrest','Не удалось найти данные', $modelImport->errors, Yii::$app->user->identity);
                    Yii::$app->getSession()->setFlash('error','Не удалось найти данные');
                }

            } else {
                HistoryAdd::import(2, 'find/arrest','Ошибка при импортировании файла для поиска', $modelImport->errors, Yii::$app->user->identity);
                Yii::$app->getSession()->setFlash('error','Ошибка при импортировании файла для поиска');
            }
        }

        return $this->render('arrest', [
            'modelImport' => $modelImport,
        ]);
    }
}
