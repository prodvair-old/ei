<?php

namespace backend\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

use common\models\db\Manager;

/**
 * ManagerController.
 */
class ManagerController extends Controller
{
    /**
     * Fill in items for dropdown list.
     * @param string  $search a part of item
     * @param integer $selected item
     * @return json array of {id: integer, text: string}
     * @throws ForbiddenHttpException if this is not an ajax request
     */
	public function actionFillin($search = '', $select = 0)
	{
		if (Yii::$app->getRequest()->isAjax) {
            return $this->asJson(['results' => Manager::getItems($search, $select)]);
		} else
			throw new ForbiddenHttpException('Only ajax request suitable.');
	}
}
