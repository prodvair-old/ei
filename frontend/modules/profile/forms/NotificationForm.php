<?php
namespace frontend\modules\profile\forms;

use yii\base\Model;

class NotificationForm extends Model
{ 
    public $new_picture;
    public $new_report;
    public $price_reduction;
    public $user_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['new_picture', 'new_report', 'price_reduction'], 'boolean'],
            [['new_picture', 'new_report', 'price_reduction'], 'default', 'value' => true],
            [['user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'new_picture' => 'К лоту в избранном добавлено новое фото',
			'new_report' => 'К лоту в избранном добавлен отчет',
			'price_reduction' => 'По лоту в избранном снижена цена',
        ];
    }
}
