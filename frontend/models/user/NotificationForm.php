<?php
namespace frontend\models\user;

use Yii;
use yii\base\Model;

class NotificationForm extends Model
{ 
    public $new_picture;
    public $new_report;
    public $price_reduction;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['new_picture', 'new_report', 'price_reduction'], 'boolean'],
            [['new_picture', 'new_report', 'price_reduction'], 'default', 'value' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'new_picture' => 'К лоту добавлено новое фото',
			'new_report' => 'К лоту добавлен отчет',
			'price_reduction' => 'По лоту снижена цена',
        ];
    }
}
