<?php


namespace frontend\modules\forms;

use yii\base\Model;

/**
 * Class SubscribeForm
 * @package frontend\modules\forms
 */
class SubscribeForm extends Model
{
    public $tariffId;

    public $userId;

    public $fee;

    public $term;


    public function rules()
    {
        return [
            [['tariffId', 'userId', 'fee', 'term'], 'required'],
            [['tariffId', 'userId', 'fee', 'term'], 'number'],
        ];
    }
}