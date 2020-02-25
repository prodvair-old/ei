<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\data\Pagination;

use backend\models\UserAccess;
use backend\models\Editors\LotEditor;
use backend\models\Editors\TorgEditor;

use common\models\Query\Lot\LotsAll;
use backend\models\HistoryAdd;

/**
 * Lots controller
 */
class LotsController extends Controller
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
                        'actions' => ['update', 'add', 'index', 'image-del'],
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
    public function actionIndex()
    {
        if (!UserAccess::forManager('lots')) {
            return $this->goHome();
        }

        $lots = LotsAll::find()->joinWith('torg')->orderBy('torg."publishedDate" DESC');
        
        return $this->render('index', ['lots' => $lots]);
    }

    public function actionUpdate()
    {
        if (!UserAccess::forManager('lots', 'edit')) {
            return $this->goHome();
        }

        $get = Yii::$app->request->get();

        $modelLot = LotEditor::findOne($get['id']);
        $lot = LotsAll::findOne($get['id']);

        if ($modelLot->load(Yii::$app->request->post()) && $modelLot->validate()) {

            if ($modelLot->uploads = UploadedFile::getInstances($modelLot, 'uploads')) {
                $modelLot->uploadImages();
            }

            if ($modelLot->update()) {
                Yii::$app->session->setFlash('success', "Изменения лота успешно применены");
                HistoryAdd::edit(1, 'lots/update','Редактирование лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при сохранении новых данных лота");
                HistoryAdd::edit(2, 'lots/update','Ошибка при редактирования лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
            }

            return $this->redirect(['lots/update', 'id' => $lot->id]);
        }

        if (UserAccess::forManager('torgs')) {
            $modelTorg = TorgEditor::findOne($modelLot->torgId);
        }

        return $this->render('update', ['modelLot' => $modelLot, 'modelTorg' => $modelTorg, 'lot' => $lot]);
    }
    // public function actionCreate()
    // {
    //     $modelCustomer = new Customer;
    //     $modelsAddress = [new Address];
    //     if ($modelCustomer->load(Yii::$app->request->post())) {

    //         $modelsAddress = Model::createMultiple(Address::classname());
    //         Model::loadMultiple($modelsAddress, Yii::$app->request->post());

    //         // ajax validation
    //         if (Yii::$app->request->isAjax) {
    //             Yii::$app->response->format = Response::FORMAT_JSON;
    //             return ArrayHelper::merge(
    //                 ActiveForm::validateMultiple($modelsAddress),
    //                 ActiveForm::validate($modelCustomer)
    //             );
    //         }

    //         // validate all models
    //         $valid = $modelCustomer->validate();
    //         $valid = Model::validateMultiple($modelsAddress) && $valid;
            
    //         if ($valid) {
    //             $transaction = \Yii::$app->db->beginTransaction();
    //             try {
    //                 if ($flag = $modelCustomer->save(false)) {
    //                     foreach ($modelsAddress as $modelAddress) {
    //                         $modelAddress->customer_id = $modelCustomer->id;
    //                         if (! ($flag = $modelAddress->save(false))) {
    //                             $transaction->rollBack();
    //                             break;
    //                         }
    //                     }
    //                 }
    //                 if ($flag) {
    //                     $transaction->commit();
    //                     return $this->redirect(['view', 'id' => $modelCustomer->id]);
    //                 }
    //             } catch (Exception $e) {
    //                 $transaction->rollBack();
    //             }
    //         }
    //     }

    //     return $this->render('create', [
    //         'modelCustomer' => $modelCustomer,
    //         'modelsAddress' => (empty($modelsAddress)) ? [new Address] : $modelsAddress
    //     ]);
    // }

    public function actionImageDel()
    {
        if (!UserAccess::forManager('lots', 'edit')) {
            return $this->goHome();
        }

        $get = Yii::$app->request->get();

        $lot = LotsAll::findOne($get['lotId']);

        $images = [];

        foreach ($lot->images as $id => $image) {
            if ($id != $get['id']) {
                $images[$id] = $image;
            }
        }

        $lot->images = $images;

        if ($lot->update()) {
            Yii::$app->session->setFlash('success', "Картинка успешно удалена");
        } else {
            Yii::$app->session->setFlash('error', "Ошибка при удалении картинки №".$get['id']);
        }

        return $this->redirect(['lots/update', 'id' => $lot->id]);
    }
}
