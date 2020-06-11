<?php

namespace frontend\modules\models;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveQuery;

/**
 * Class CategoryQuery
 * @package frontend\modules\models
 */
class CategoryQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsBehavior::className(),
        ];
    }
}
