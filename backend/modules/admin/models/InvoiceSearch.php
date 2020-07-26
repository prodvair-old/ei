<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Invoice;

class InvoiceSearch extends Invoice
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'product'], 'integer'],
            ['paid', 'boolean'],
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['recordsPerPage'],
            ],
            'sort' => [
                'attributes' => [
                    'id',
                    'name',
                ],
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterwhere(['id' => $this->id])
            ->andFilterWhere(['product' => $this->product])
            ->andFilterWhere(['paid' => $this->paid]);
        
        return $dataProvider;
    }
}
