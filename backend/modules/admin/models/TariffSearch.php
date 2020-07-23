<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Tariff;
use common\components\IntCode;
use common\components\Property;

class TariffSearch extends Tariff
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
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
        $query->where(['id' => $this->id])
            ->andWhere(['like', 'name',  $this->name]);
        
        return $dataProvider;
    }
}
