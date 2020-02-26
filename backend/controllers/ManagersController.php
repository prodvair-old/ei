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

use common\models\Query\Lot\Managers;
use backend\models\HistoryAdd;

/**
 * Managers controller
 */
class ManagersController extends Controller
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
                        'actions' => ['list', 'update', 'create', 'index', 'image-del'],
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
    public function actionList($q = null, $id = null)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (is_null($id)) {
            $managers = Managers::find()->limit(6);

            if (!is_null($q)) {
                $managers->where(['like', 'lower("fullName")', mb_strtolower($q, 'UTF-8')]);
            }

            foreach ($managers->all() as $manager) {
                switch ($manager->type) {
                    case 'arbitr':
                        $type = 'Арбитражный управлющим';
                        break;
                    case 'organizer':
                        $type = 'Организация';
                        break;
                    case 'publisher':
                        $type = 'Публикатор';
                        break;
                }

                $managersList['results'][] = [
                    'id' => $manager->id,
                    'text' => '<b>'.$manager->fullName.'</b> — '.$type.''
                ];
            }
        } else {
            $manager = Managers::findOne(['id' => $id]);

            switch ($manager->type) {
                case 'arbitr':
                    $type = 'Арбитражный управлющим';
                    break;
                case 'organizer':
                    $type = 'Организация';
                    break;
                case 'publisher':
                    $type = 'Публикатор';
                    break;
            }

            $managersList['results'] = ['id' => $id, 'text' => '<b>'.$manager->fullName.'</b> — '.$type.''];
        }
        
        return $managersList;
    }
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
    public function actionCreate()
    {
        if (!UserAccess::forManager('lots', 'add')) {
            return $this->goHome();
        }

        // $get = Yii::$app->request->get();

        $modelLot = new LotEditor();

        $modelTorg = new TorgEditor();

        if ($modelTorg->load(Yii::$app->request->post()) && $modelTorg->validate()) {

            if ($modelTorg->save()) {
                Yii::$app->session->setFlash('success', "Новый торг успешно добавлен");
                HistoryAdd::edit(1, 'lots/create','Добавлен новый торг №'.$modelTorg->id, ['torgId' => $modelTorg->id], Yii::$app->user->identity);

                $modelLot->torgId = $modelTorg->id;

                if ($modelLot->load(Yii::$app->request->post()) && $modelLot->validate()) {

                    if ($modelLot->uploads = UploadedFile::getInstances($modelLot, 'uploads')) {
                        $modelLot->uploadImages();
                    }
        
                    if ($modelLot->save()) {
                        Yii::$app->session->setFlash('success', "Новый лот успешно добавлен");
                        HistoryAdd::edit(1, 'lots/create','Добавлен новый лот №'.$modelLot->id, ['lotId' => $modelLot->id], Yii::$app->user->identity);
                    } else {
                        Yii::$app->session->setFlash('error', "Ошибка при добавлении нового лота");
                        HistoryAdd::edit(2, 'lots/create','Ошибка при добавлении нового лота №'.$modelLot, ['lotId' => $modelLot->id], Yii::$app->user->identity);
                    }
        
                    return $this->redirect(['lots/update', 'id' => $modelLot->id]);
                }
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при добавлении нового торга");
                HistoryAdd::edit(2, 'lots/create','Ошибка при редактирования торга №'.$modelTorg->id, ['torgId' => $modelTorg->id], Yii::$app->user->identity);
            }

        }

        return $this->render('create', ['modelLot' => $modelLot, 'modelTorg' => $modelTorg]);
    }

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
