<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Owner;

class OwnerSearch extends Owner
{
    public $title;
    
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Owner::find()
            ->select('owner.id, title, email, phone, website, status, activity')
            ->innerJoin('{{%organization}}', '`organization`.`parent_id`=`owner`.`id` AND organization.model=' .self::INT_CODE)
            ->innerJoin('{{%place}}', '`place`.`parent_id`=`owner`.`id` AND place.model=' .self::INT_CODE)
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['recordsPerPage'],
            ],
            'sort' => [
                'attributes' => [
                    'id',
                    'title',
                ],
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterWhere(['owner.id' => $this->id])
            ->andFilterWhere(['like', 'organization.title', $this->title]);
        return $dataProvider;
    }
}
