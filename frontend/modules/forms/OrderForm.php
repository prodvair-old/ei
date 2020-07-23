<?php


namespace frontend\modules\forms;


use common\models\db\Order;
use yii\base\Model;

/**
 * Class OrderForm
 * @package frontend\modules\models
 */
class OrderForm extends Model
{
    public $lot_id;

    public $user_id;

    public $bid_price;

    public $checkPolicy;


    public function rules()
    {
        return [
            [['lot_id', 'user_id', 'checkPolicy'], 'required'],
            [['bid_price','lot_id', 'user_id'], 'number'],
        ];
    }

    /**
     * @param Order $model
     * @param array $post
     * @return void
     */
    public function loadFields(Order $model, array $post) {
        $model->user_id = $post[ 'user_id' ];
        $model->lot_id = $post[ 'lot_id' ];

        if ($post[ 'bid_price' ]) {
            $model->bid_price = intval($post[ 'bid_price' ]);
        }
    }

}