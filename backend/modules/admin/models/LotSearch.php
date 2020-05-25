<?php

namespace backend\modules\admin\models;

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

    public function search($params, $offset = 0)
    {
        $query = Lot::find()
            ->joinWith('torg', true, 'INNER JOIN')
            ->joinWith('categories', true, 'LEFT JOIN')
            ->limit(Yii::$app->params['recordsPerPage'])
            ->offset($offset);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'attributes' => [
                    'id',
                    'title',
                    'start_price',
                    'end_at',
                ],
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterWhere(['lot.id' => $this->id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['reason' => $this->reason])
            ->andFilterWhere(['torg.property' => $this->property]);
        if ($this->category_id) {
            // adding category and it childrens to the filter
            $category = Category::findOne($this->category_id);
            $ids = []; $ids[] = $category->id;
            foreach($category->children()->all() as $child)
                $ids[] = $child->id;
            $query->andFilterWhere(['in', 'category_id', $ids]);
        }
        
        return $dataProvider;
    }
}
