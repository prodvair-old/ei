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
        if (!UserAccess::forAgent('find', 'arrest')) {
            return $this->goHome();
        }
        $modelImport = new ImportFIleForm();

        if(Yii::$app->request->post()){
            ini_set('memory_limit', '1024M');

            $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport,'fileImport');
            if($modelImport->fileImport && $modelImport->validate()){
                $result = $modelImport->excelArrest();

                if ($result['first'][0] != null || $result['second'][0] != null) {
                    HistoryAdd::export(1, 'find/arrest', 'Иммущество успешно экспортировано', null, Yii::$app->user->identity);
                
                    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

                    Excel::export([
                        'isMultipleSheet' => true,
                        'models' => [
                            'Грубый поиск' => $result['first'], 
                            'Лёгкий поиск' => $result['second'], 
                        ],
                        'columns' => [
                            'Грубый поиск' => [
                                'inn:text',
                                'name:text',
                                'title:text',
                                'torg:text',
                                'publication:datetime',
                                'auction:datetime',
                                'form:text',
                                'repeat:text',
                                'winner:text',
                                'price:text',
                                'url:text',
                            ],
                            'Лёгкий поиск' => [
                                'inn:text',
                                'name:text',
                                'title:text',
                                'torg:text',
                                'publication:datetime',
                                'auction:datetime',
                                'form:text',
                                'repeat:text',
                                'winner:text',
                                'price:text',
                                'url:text',
                            ], 
                        ],
                        'headers' => [
                            'Грубый поиск' => [
                                'inn' => 'ИНН',
                                'name' => 'Наименование/ФИО',
                                'title' => 'Наименование имущества',
                                'torg' => 'Источник: torgi.gov.ru/bankrot.fedresurs.ru',
                                'publication' => 'Дата публикации',
                                'auction' => 'Дата торгов',
                                'form' => 'Способ реализации',
                                'repeat' => 'Повторные',
                                'winner' => 'Победитель',
                                'price' => 'Цена',
                                'url' => 'Ссылка на торг',
                            ],
                            'Лёгкий поиск' => [
                                'inn' => 'ИНН',
                                'name' => 'Наименование/ФИО',
                                'title' => 'Наименование имущества',
                                'torg' => 'Источник: torgi.gov.ru/bankrot.fedresurs.ru',
                                'publication' => 'Дата публикации',
                                'auction' => 'Дата торгов',
                                'form' => 'Способ реализации',
                                'repeat' => 'Повторные',
                                'winner' => 'Победитель',
                                'price' => 'Цена',
                                'url' => 'Ссылка на торг',
                            ],
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
