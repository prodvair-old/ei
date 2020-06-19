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
        $on = [];
        $on['organization'] = '"organization"."parent_id"="owner"."id" AND "organization"."model"=' . self::INT_CODE;
        $on['place'] = '"place"."parent_id"="owner"."id" AND "place"."model"=' . self::INT_CODE;
        if ($this->db->driverName === 'mysql') {
            $on['organization'] = str_replace('"', '', $on['organization']);
            $on['place'] = str_replace('"', '', $on['place']);
        }
        $query = Owner::find()
            ->select('owner.id, title, email, phone, website, status, activity')
            ->innerJoin('{{%organization}}', $on['organization'])
            ->innerJoin('{{%place}}', $on['place'])
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
