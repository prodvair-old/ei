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
use common\models\Query\Lot\LotCategorys;
use common\models\Query\LotsCategory;
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
                        'actions' => ['update', 'create', 'index', 'image-del', 'published', 'category-list', 'delete'],
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
    public function actionCategoryList($type = null, $category = null, $q = null, $id = null)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (is_null($type) && is_null($category)) {
            return null;
        }

        $categoryItem = LotsCategory::findOne(['id'=>$category]);

        switch ($type) {
            case '1':
                $subcategorys = $categoryItem->bankrupt_categorys;
                break;
            case '2':
                $subcategorys = $categoryItem->arrest_categorys;
                break;
            case '3':
                $subcategorys = $categoryItem->zalog_categorys;
                break;
        }

        if (is_null($id)) {
            foreach ($subcategorys as $key => $subcategory) {
                if (!is_null($q)) {
                    if (strpos($subcategory['name'], $q)) {
                        $categorysList['results'][] = [
                            'id' => $key,
                            'text' => '<b>'.$subcategory['name'].'</b>'
                        ];
                    }
                } else {
                    $categorysList['results'][] = [
                        'id' => $key,
                        'text' => '<b>'.$subcategory['name'].'</b>'
                    ];
                }
            }

        } else {
            foreach ($subcategorys as $key => $subcategory) {
                foreach ($id as $value) {
                    if ($key == $value) {
                        $categorysList['results'][] = [
                            'id' => $key,
                            'text' => '<b>'.$subcategory['name'].'</b>'
                        ];
                    }
                }
            }
        }
        
        return $categorysList;
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

        $modelTorg = TorgEditor::findOne($modelLot->torgId);

        foreach (LotCategorys::find()->where(['lotId' => $get['id']])->all() as $subCategoryItem) {
            $modelLot->subCategorys[] = $subCategoryItem->categoryId;
            $subCategorysItems[$subCategoryItem->categoryId] = $subCategoryItem->name;
        }

        foreach (LotsCategory::find()->all() as $categoryItem) {
            switch ($modelTorg->typeId) {
                case '1':
                    $subcategorys = $categoryItem->bankrupt_categorys;
                    break;
                case '2':
                    $subcategorys = $categoryItem->arrest_categorys;
                    break;
                case '3':
                    $subcategorys = $categoryItem->zalog_categorys;
                    break;
            }

            foreach ($subcategorys as $key => $item) {
                foreach ($modelLot->subCategorys as $subcategory) {
                    if ($key == $subcategory) {
                        $modelLot->categorys = $categoryItem->id;
                    }
                }
                
            }
        }

        if ($modelTorg->load(Yii::$app->request->post()) && $modelTorg->validate()) {

            if ($modelTorg->update()) {
                Yii::$app->session->setFlash('success', "Изменения торга успешно применены");
                HistoryAdd::edit(1, 'lots/create','Редактирование торга №'.$modelTorg->id, ['torgId' => $modelTorg->id], Yii::$app->user->identity);
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при сохранении новых данных торга");
                HistoryAdd::edit(2, 'lots/create','Ошибка при редактирования торга №'.$modelTorg->id, ['torgId' => $modelTorg->id], Yii::$app->user->identity);
            }

        }

        if ($modelLot->load(Yii::$app->request->post()) && $modelLot->validate()) {

            if ($modelLot->uploads = UploadedFile::getInstances($modelLot, 'uploads')) {
                $modelLot->uploadImages();
            }

            if ($modelLot->update()) {
                if ($modelLot->setCategorys($modelTorg->typeId)) {
                    Yii::$app->session->setFlash('success', "Изменения лота успешно применены");
                    HistoryAdd::edit(1, 'lots/update','Редактирование лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
                } else {
                    Yii::$app->session->setFlash('error', "Ошибка при добавлении категории");
                    HistoryAdd::edit(2, 'lots/update','Ошибка при добавлении категории лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
                }
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при сохранении новых данных лота");
                HistoryAdd::edit(2, 'lots/update','Ошибка при редактирования лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
            }

            return $this->redirect(['lots/update', 'id' => $lot->id]);
        }

        return $this->render('update', ['modelLot' => $modelLot, 'modelTorg' => $modelTorg, 'lot' => $lot, 'subCategorysItems' => $subCategorysItems]);
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
                HistoryAdd::add(1, 'lots/create','Добавлен новый торг №'.$modelTorg->id, ['torgId' => $modelTorg->id], Yii::$app->user->identity);
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при добавлении нового торга");
                HistoryAdd::add(2, 'lots/create','Ошибка при добавления торга №'.$modelTorg->id, ['torgId' => $modelTorg->id], Yii::$app->user->identity);
            }

        }

        $modelLot->torgId = $modelTorg->id;

        if ($modelLot->load(Yii::$app->request->post()) && $modelLot->validate()) {

            if ($modelLot->uploads = UploadedFile::getInstances($modelLot, 'uploads')) {
                $modelLot->uploadImages();
            }

            if ($modelLot->save()) {
                if ($modelLot->setCategorys($modelTorg->typeId)) {
                    Yii::$app->session->setFlash('success', "Изменения лота успешно применены");
                    HistoryAdd::add(1, 'lots/update','Редактирование лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
                } else {
                    Yii::$app->session->setFlash('error', "Ошибка при добавлении категории");
                    HistoryAdd::add(2, 'lots/update','Ошибка при добавлении категории лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
                }
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при добавлении нового лота");
                HistoryAdd::add(2, 'lots/create','Ошибка при добавлении нового лота №'.$modelLot, ['lotId' => $modelLot->id], Yii::$app->user->identity);
            }

            return $this->redirect(['lots/update', 'id' => $modelLot->id]);
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
            HistoryAdd::remove(1, 'lots/image-del','Удаление картинки у лота №'.$lot->id, ['imgId'=> $get['id'], 'lotId' => $lot->id], Yii::$app->user->identity);
        } else {
            Yii::$app->session->setFlash('error', "Ошибка при удалении картинки №".$get['id']);
            HistoryAdd::remove(2, 'lots/image-del','Ошибка при удалении картинки №'.$get['id'].', у лота №'.$lot->id, ['imgId'=> $get['id'], 'lotId' => $lot->id], Yii::$app->user->identity);
        }

        return $this->redirect(['lots/update', 'id' => $lot->id]);
    }
    public function actionPublished()
    {
        if (!UserAccess::forManager('lots', 'edit')) {
            return $this->goHome();
        }

        $get = Yii::$app->request->get();

        $lot = LotsAll::findOne($get['id']);

        $lot->published = !$lot->published;

        if ($lot->update()) {
            Yii::$app->session->setFlash('success', "Лот успешно опубликован");
            if ($lot->published) {
                HistoryAdd::published(1, 'lots/published','Публикация лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
            } else {
                HistoryAdd::unPublished(1, 'lots/published','Снятие с публикации лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
            }
        } else {
            Yii::$app->session->setFlash('error', "Ошибка при публикации лота №".$get['id']);
            if ($lot->published) {
                HistoryAdd::published(2, 'lots/published','Ошибка при публикация лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
            } else {
                HistoryAdd::unPublished(2, 'lots/published','Ошибка при снятий с публикации лота №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
            }
        }

        return $this->redirect(['lots/update', 'id' => $lot->id]);
    }
    public function actionDelete()
    {
        if (!UserAccess::forManager('lots', 'delete')) {
            return $this->goHome();
        }

        $get = Yii::$app->request->get();

        $lot = LotsAll::findOne($get['id']);

        if ($lot->delete()) {
            Yii::$app->session->setFlash('success', "Лот успешно удалён");
            HistoryAdd::remove(1, 'lots/delete','Удалён лот №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
            return $this->redirect(['lots/index']);
        } else {
            Yii::$app->session->setFlash('error', "Ошибка при удалении лота №".$get['id']);
            HistoryAdd::remove(2, 'lots/delete','Удалён лот №'.$lot->id, ['lotId' => $lot->id], Yii::$app->user->identity);
            return $this->redirect(['lots/update', 'id' => $lot->id]);
        }
    }
}