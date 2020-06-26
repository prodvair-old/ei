<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Order;
use common\models\db\User;

class OrderSearch extends Order
{
    public $lot_id;
    public $title;
    public $username;
    public $full_name;
    
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['lot_id'], 'integer'],
            [['title', 'username', 'full_name'], 'safe'],
        ];
    }

    public function search($params, $offset = 0)
    {
        $query = Order::find()
            ->select('order.id, lot_id, user_id, title, username, first_name, last_name, phone, bid_price, order.created_at')
            ->distinct(false)
            ->innerJoin('{{%lot}}', 'order.lot_id=lot.id')
            ->innerJoin('{{%torg}}', 'lot.torg_id=torg.id')
            ->innerJoin('{{%user}}', 'order.user_id=user.id')
            ->leftJoin('{{%profile}}', 'user.id=profile.parent_id AND profile.model=' . User::INT_CODE);

        $user = Yii::$app->user;

        // bankrupt property, arbitration manager
        if ($user->identity->role == User::ROLE_ARBITRATOR && ($manager_id = $user->identity->getManagerId()))
            $query->innerJoin('{{%torg_debtor}}', 'torg.id=torg_debtor.torg_id AND torg_debtor.manager_id=' . $manager_id);

        // pledge (zalog) property, ordinary user
        if ($user->identity->role == User::ROLE_AGENT)
            $query->innerJoin('{{%torg_pledge}}', 'torg.id=torg_pledge.torg_id AND torg_pledge.user_id=' . $user->id);

        $query
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['recordsPerPage'],
            ],
            'sort' => [
                'attributes' => [
                    'lot_id',
                    'title',
                    'username',
                    'created_at',
                ],
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterWhere(['lot_id' => $this->lot_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'CONCAT(first_name, last_name)', $this->full_name]);
        
        return $dataProvider;
    }
}
