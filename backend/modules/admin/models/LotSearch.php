<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Lot;
use common\models\db\Category;
use common\components\Property;

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
            ->select('lot.id, title, '.
                'lookup_status.name AS status, lookup_reason.name AS reason, lookup_property.name AS property, '.
                'category.name as category_name, start_price, torg.end_at')
            ->innerJoin('{{%torg}}', 'lot.torg_id=torg.id')
            ->innerJoin('{{%lot_category}}', 'lot.id=lot_category.lot_id')
            ->innerJoin('{{%category}}', 'lot_category.category_id=category.id')
            ->innerJoin('{{%lookup}} AS lookup_status', 'lot.status=lookup_status.code AND lookup_status.property_id='. Property::LOT_STATUS)
            ->innerJoin('{{%lookup}} AS lookup_reason', 'lot.reason=lookup_reason.code AND lookup_reason.property_id='. Property::LOT_REASON)
            ->innerJoin('{{%lookup}} AS lookup_property', 'torg.property=lookup_property.code AND lookup_property.property_id='. Property::TORG_PROPERTY)
            ->limit(Yii::$app->params['recordsPerPage'])
            ->offset($offset)
            ->asArray();

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
        if ($this->category_id > Category::ROOT)
            $query->andFilterWhere(['lot_category.category_id' => $this->category_id]);
        
        return $dataProvider;
    }
}
