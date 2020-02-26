<?php

namespace backend\models;

use Yii;

/**
 */
class AddField extends \yii\base\Model
{
    /**
     * {@inheritdoc}
     */
    public $name;

    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название поля',
        ];
    }
}
