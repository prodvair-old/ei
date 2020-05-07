<?php

namespace console\traits;

use yii\console\Exception;

trait Keeper
{
    /**
     * @param ActiveRecord $model
     * @param array $arr 
     * @param array $elm
     * @throw yii\console\Exception
     */
    public function validateAndKeep($model, &$arr, $elm)
    {
        if ($model->validate()) {
            $arr[] = $elm;
            return true;
        } else
            throw new Exception(print_r($model->getErrors()));
    }
}
