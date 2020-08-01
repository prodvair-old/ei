<?php


namespace frontend\modules\forms;

use yii\base\Model;

/**
 * Class OrderForm
 * @package frontend\modules\models
 */
class ReportForm extends Model
{
    public $reportId;

    public $userId;

    public $cost;

    public $returnUrl;

    public $checkPolicy;


    public function rules()
    {
        return [
            [['reportId', 'userId', 'cost', 'returnUrl', 'checkPolicy'], 'required'],
            [['reportId', 'userId', 'cost'], 'number'],
            [['returnUrl'], 'string'],
        ];
    }
}