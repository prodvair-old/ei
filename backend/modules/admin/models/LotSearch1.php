<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\SqlDataProvider;

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

    public function search($params)
    {
        // load the search form data and validate
        $params = ($this->load($params) && $this->validate())
            ? 
                [
                    'lot.id'                   => $this->id,
                    'status'                   => $this->status,
                    'reason'                   => $this->reason,
                    'torg.property'            => $this->property,
                    'lot_category.category_id' => $this->category_id,
                ]
            :
                [];
       // integer fields
        $a = [];
        foreach($params as $field => $value) {
            if ($value)
                $a[] = "($field = $value)";
        }
        // text field
        if ($this->title)
            $a[] = "(title LIKE '%{$this->title}%')";
        $where = 'WHERE ' . ($a ? implode(' AND ', $a) : '1') . ' ';

        $select = 'SELECT lot.id, title, 
            lookup_status.name AS status, 
            lookup_reason.name AS reason, 
            lookup_property.name AS property, 
            category.name as category_name, start_price, torg.end_at FROM lot 
            INNER JOIN torg ON (lot.torg_id=torg.id)
            INNER JOIN lot_category ON (lot.id=lot_category.lot_id)
            INNER JOIN category ON (lot_category.category_id=category.id)
            INNER JOIN lookup AS lookup_status ON (lot.status=lookup_status.code AND lookup_status.property_id='. Property::LOT_STATUS .')
            INNER JOIN lookup AS lookup_reason ON (lot.reason=lookup_reason.code AND lookup_reason.property_id='. Property::LOT_REASON .')
            INNER JOIN lookup AS lookup_property ON (torg.property=lookup_property.code AND lookup_property.property_id='. Property::TORG_PROPERTY .')';
        
        $count = Yii::$app->params['recordsPerPage'];

        $dataProvider = new SqlDataProvider([
            'sql' => $select . $where,
            'totalCount' => $count,
            'pagination' => false,
            'sort' => [
                'attributes' => [
                    'lot.id',
                    'title',
                    'start_price',
                    'torg.end_at',
                ],
            ],
            'limit' => Yii::$app->params['recordsPerPage'],
        ]);

        return $dataProvider;
    }
}
