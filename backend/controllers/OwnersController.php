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
use backend\models\Editors\OwnerrEditor;

use common\models\Query\Lot\Owners;
use backend\models\HistoryAdd;

/**
 * Owners controller
 */
class OwnersController extends Controller
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
                        'actions' => ['list', 'update', 'create', 'index', 'delete'],
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
            $owners = Owners::find()->limit(6);

            if (!is_null($q)) {
                $owners->where(['like', 'lower("title")', mb_strtolower($q, 'UTF-8')]);
            }

            foreach ($owners->all() as $owner) {
                switch ($owner->type) {
                    case 'bank':
                        $type = 'Банк';
                        break;
                    case 'company':
                        $type = 'Организация';
                        break;
                }

                $ownersList['results'][] = [
                    'id' => $owner->id,
                    'text' => '<b>'.$owner->title.'</b> — '.$type.''
                ];
            }
        } else {
            $owner = Owners::findOne(['id' => $id]);

            switch ($owner->type) {
                case 'bank':
                    $type = 'Банк';
                    break;
                case 'company':
                    $type = 'Организация';
                    break;
            }

            $ownersList['results'] = ['id' => $id, 'text' => '<b>'.$owner->title.'</b> — '.$type.''];
        }
        
        return $ownersList;
    }
    public function actionIndex()
    {
        if (!UserAccess::forManager('owners')) {
            return $this->goHome();
        }

        $owners = Owners::find();
        
        return $this->render('index', ['owners' => $owners]);
    }

    public function actionUpdate()
    {
        if (!UserAccess::forManager('owners', 'edit')) {
            return $this->goHome();
        }

        $get = Yii::$app->request->get();

        $model = OwnerrEditor::findOne($get['id']);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->bg = UploadedFile::getInstance($model, 'bg')) {
                $model->uploadBg();
            }

            if ($model->upload = UploadedFile::getInstance($model, 'upload')) {
                $model->uploadLogo();
            }

            if ($model->update()) {
                Yii::$app->session->setFlash('success', "Изменения организации успешно применены");
                HistoryAdd::edit(1, 'owners/update','Редактирование организации №'.$model->id, ['ownerId' => $model->id], Yii::$app->user->identity);
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при сохранении новых данных организации");
                HistoryAdd::edit(2, 'owners/update','Ошибка при редактирования организации №'.$model->id, ['ownerId' => $model->id], Yii::$app->user->identity);
            }

            return $this->redirect(['owners/update', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model]);
    }
    public function actionCreate()
    {
        if (!UserAccess::forManager('owners', 'add')) {
            return $this->goHome();
        }
        
        $model = new OwnerrEditor();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {

                if ($model->bg = UploadedFile::getInstance($model, 'bg')) {
                    $model->uploadBg();
                    $model->update();
                }
    
                if ($model->upload = UploadedFile::getInstance($model, 'upload')) {
                    $model->uploadLogo();
                    $model->update();
                }

                Yii::$app->session->setFlash('success', "Новая организация успешно добавлена");
                HistoryAdd::edit(1, 'owners/update','Добавлена новая организация №'.$model->id, ['ownerId' => $model->id], Yii::$app->user->identity);

                return $this->redirect(['owners/update', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при добавлении организации");
                HistoryAdd::edit(2, 'owners/update','Ошибка при добавлении организации', null, Yii::$app->user->identity);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionImageDel()
    {
        if (!UserAccess::forManager('owners', 'edit')) {
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
    public function actionDelete()
    {
        if (!UserAccess::forManager('owners', 'delete')) {
            return $this->goHome();
        }

        $get = Yii::$app->request->get();

        $owner = OwnerrEditor::findOne($get['id']);

        if ($owner->delete()) {
            Yii::$app->session->setFlash('success', "Организация успешно удалёно");
            HistoryAdd::remove(1, 'owners/delete','Удалёна организация №'.$get['id'], ['ownerId' => $get['id']], Yii::$app->user->identity);
            return $this->redirect(['owners/index']);
        } else {
            Yii::$app->session->setFlash('error', "Ошибка при удалении организации №".$get['id']);
            HistoryAdd::remove(2, 'owners/delete','Ошибка удаления организации №'.$get['id'], ['ownerId' => $get['id']], Yii::$app->user->identity);
            return $this->redirect(['owners/update', 'id' => $get['id']]);
        }
    }
}
