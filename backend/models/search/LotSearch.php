<?php

namespace backend\models\search;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Lot;
use common\models\db\Category;

class LotSearch extends Lot
{
    public $property;
    public $category_id = 0;
    
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'status', 'reason', 'property', 'category_id'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Lot::find()->joinWith('categories', true, 'INNER JOIN');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['recordsPerPage'],
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['reason' => $this->reason])
            ->andFilterWhere(['property' => $this->property]);

        if ($this->category_id) {
            $category = Category::findOne($this->category_id);
            $category_ids = []; $category_ids[] = $this->category_id;
            foreach($category->children()->all() as $child)
                $category_ids[] = $child->id;
            $query->andFilterWhere(['in', 'lot_category.category_id', $category_ids]);
        }
        
        return $dataProvider;
    }
}
