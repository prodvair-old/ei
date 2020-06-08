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
        $join = [];
        $join['organization'] = '"organization"."parent_id"="owner"."id" AND "organization"."model"=' . self::INT_CODE;
        $join['place'] = '"place"."parent_id"="owner"."id" AND "place"."model"=' . self::INT_CODE;
        if ($this->db->driverName === 'mysql') {
            $join['organization'] = str_replace('"', '', $join['organization']);
            $join['place'] = str_replace('"', '', $join['place']);
        }
        $query = Owner::find()
            ->select('owner.id, title, email, phone, website, status, activity')
            ->innerJoin('{{%organization}}', $join['organization'])
            ->innerJoin('{{%place}}', $join['place'])
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
