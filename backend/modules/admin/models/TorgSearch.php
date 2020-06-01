<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Torg;

class TorgSearch extends Torg
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'msg_id', 'property', 'offer'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function search($params, $offset = 0)
    {
        $query = Torg::find()
            ->limit(Yii::$app->params['recordsPerPage'])
            ->offset($offset);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'attributes' => [
                    'id',
                    'msg_id',
                    'started_at',
                    'end_at',
                ],
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterWhere(['d' => $this->id])
            ->andFilterWhere(['like', 'msg_id', $this->msg_id])
            ->andFilterWhere(['property' => $this->property])
            ->andFilterWhere(['offer' => $this->offer]);
        
        return $dataProvider;
    }
}
