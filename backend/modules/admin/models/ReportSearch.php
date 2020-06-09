<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Report;
use common\components\IntCode;
use common\components\Property;

class ReportSearch extends Report
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'status'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function search($params, $offset = 0)
    {
        $join = [];
        $join['user'] = '"report"."user_id"="user.id"';
        $join['profile'] = '"report"."user_id"="profile"."parent_id" AND model='. IntCode::USER;
        if ($this->db->driverName === 'mysql') {
            $join['user'] = str_replace('"', '', $join['user']);
            $join['profile'] = str_replace('"', '', $join['profile']);
        }
        $query = Report::find()
            ->select('report.id, lot.id as lot_id, report.title, lookup_status.name AS status, lookup_property.name AS property, cost, '.
                'user.username, profile.first_name, profile.last_name')
            ->innerJoin('{{%lot}}', 'report.lot_id=lot.id')
            ->innerJoin('{{%torg}}', 'lot.torg_id=torg.id')
            ->innerJoin('{{%user}}', $join['user'])
            ->innerJoin('{{%profile}}', $join['profile'])
            ->innerJoin('{{%lookup}} AS lookup_status', 'report.status=lookup_status.code AND lookup_status.property_id='. Property::REPORT_STATUS)
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
                ],
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterWhere(['report.id' => $this->id])
            ->andFilterWhere(['like', 'title',  $this->title])
            ->andFilterWhere(['status' => $this->status]);
        
        return $dataProvider;
    }
}
