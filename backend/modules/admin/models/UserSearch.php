<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\User;

class UserSearch extends User
{
    public $full_name;
    
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'status', 'role'], 'integer'],
            [['username', 'full_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $on = [];
        $on['profile'] = '"profile"."parent_id"="user"."id" AND model='. self::INT_CODE;
        if ($this->db->driverName === 'mysql')
            $on['profile'] = str_replace('"', '', $on['profile']);
        $query = User::find()
            ->select('user.id, username, role, status, first_name, last_name')
            ->leftJoin('{{%profile}}', $on['profile'])
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['recordsPerPage'],
            ],
            'sort' => [
                'attributes' => [
                    'id',
                    'username',
                ],
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['role' => $this->role])
            ->andFilterWhere(['like', 'CONCAT(first_name, last_name)', $this->full_name]);
        return $dataProvider;
    }
}
