<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Torg;
use common\models\db\User;
use common\components\Property;

class TorgSearch extends Torg
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'msg_id', 'property', 'offer'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    public function search($params, $offset = 0)
    {
        $query = self::find()
            ->select('torg.id, msg_id, '.
                'torg.property AS property_id, '.
                'lookup_property.name AS property, lookup_offer.name AS offer, '.
                'started_at, end_at'
            );

        $user = Yii::$app->user;
        // bankrupt property, arbitration manager
        if ($user->identity->role == User::ROLE_ARBITRATOR && ($manager_id = $user->identity->getManagerId()))
            $query->innerJoin('{{%torg_debtor}}', 'torg.id=torg_debtor.torg_id AND torg_debtor.manager_id=' . $manager_id);

        // pledge (zalog) property, ordinary user
        if ($user->identity->role == User::ROLE_AGENT)
            $query->innerJoin('{{%torg_pledge}}', 'torg.id=torg_pledge.torg_id AND torg_pledge.user_id=' . $user->id);

        $query
            ->innerJoin('{{%lookup}} AS lookup_property', 'property=lookup_property.code AND lookup_property.property_id='. Property::TORG_PROPERTY)
            ->innerJoin('{{%lookup}} AS lookup_offer', 'offer=lookup_offer.code AND lookup_offer.property_id='. Property::TORG_OFFER)
            ->limit(Yii::$app->params['recordsPerPage'])
            ->offset($offset)
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'attributes' => [
                    'id',
                    'msg_id',
                    'started_at',
                    'end_at',
                ],
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       
        // adjust the query by adding the filters
        $query->andFilterWhere(['torg.id' => $this->id])
            ->andFilterWhere(['like', 'msg_id', $this->msg_id])
            ->andFilterWhere(['property' => $this->property])
            ->andFilterWhere(['offer' => $this->offer]);
        
        return $dataProvider;
    }
}
