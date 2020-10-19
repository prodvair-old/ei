<?php

namespace backend\modules\admin\models;

use common\models\db\Subscription;
use Yii;
use yii\data\ActiveDataProvider;

class SubscriptionSearch extends Subscription
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'user_id', 'tariff_id', 'invoice_id'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = self::find();
        $query->joinWith(['invoice'], true, 'INNER JOIN');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['recordsPerPage'],
            ],
            'sort' => [
                'attributes' => [
                    'id',
                ],
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterwhere(['id' => $this->id])
            ->andFilterWhere(['user_id' => $this->user_id])
            ->andFilterWhere(['tariff_id' => $this->tariff_id])
            ->andFilterWhere(['invoice_id' => $this->invoice_id])
        ;
        
        return $dataProvider;
    }
}
