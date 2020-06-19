<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Report;
use common\models\db\User;
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
        $on = [];
        $on['user'] = '"report"."user_id"="user"."id"';
        $on['profile'] = '"report"."user_id"="profile"."parent_id" AND model='. IntCode::USER;
        if ($this->db->driverName === 'mysql') {
            $on['user'] = str_replace('"', '', $on['user']);
            $on['profile'] = str_replace('"', '', $on['profile']);
        }
        $query = Report::find()
            ->select('report.id, lot.id as lot_id, report.title, lookup_status.name AS status, lookup_property.name AS property, cost, '.
                'user.username, profile.first_name, profile.last_name')
            ->innerJoin('{{%lot}}', 'report.lot_id=lot.id')
            ->innerJoin('{{%torg}}', 'lot.torg_id=torg.id')
            ->innerJoin('{{%user}}', $on['user'])
            ->leftJoin('{{%profile}}', $on['profile'])
            ->innerJoin('{{%lookup}} AS lookup_status', 'report.status=lookup_status.code AND lookup_status.property_id='. Property::REPORT_STATUS)
            ->innerJoin('{{%lookup}} AS lookup_property', 'torg.property=lookup_property.code AND lookup_property.property_id='. Property::TORG_PROPERTY)
            ->asArray();

        if (Yii::$app->user->identity->role <> User::ROLE_ADMIN)
            $query->where(['user_id' => Yii::$app->user->id]);

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
        $query->andFilterWhere(['report.id' => $this->id])
            ->andFilterWhere(['like', 'title',  $this->title])
            ->andFilterWhere(['report.status' => $this->status]);
        
        return $dataProvider;
    }
}
