<?php

namespace backend\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;

use common\models\db\Torg;
use common\components\IntCode;
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
        $query = Torg::find()
            ->select('torg.id, msg_id, '.
                'torg.property AS property_id, '.
                'lookup_property.name AS property, lookup_offer.name AS offer, '.
                'started_at, end_at, '.
                '(SELECT COUNT(*) FROM {{%lot}} WHERE torg.id=lot.torg_id) AS lot_count, '.
                '(
                    SELECT organization.title FROM {{%torg_pledge}}
                    INNER JOIN {{%organization}} ON (torg_pledge.owner_id=organization.parent_id AND organization.model='. IntCode::OWNER .')
                    WHERE torg.id=torg_pledge.torg_id
                ) AS owner_company,
                (
                    SELECT CONCAT(profile.first_name, profile.last_name) FROM {{%torg_pledge}}
                    INNER JOIN {{%profile}} ON (torg_pledge.user_id=profile.parent_id AND profile.model='. IntCode::USER .')
                    WHERE torg.id=torg_pledge.torg_id
                ) AS owner_person,
                (
                    SELECT organization.title FROM {{%torg_debtor}}
                    INNER JOIN {{%organization}} ON (torg_debtor.bankrupt_id=organization.parent_id AND organization.model='. IntCode::BANKRUPT .')
                    WHERE torg.id=torg_debtor.torg_id
                ) AS bankrupt_company,
                (
                    SELECT CONCAT(profile.first_name, profile.last_name) FROM {{%torg_debtor}}
                    INNER JOIN {{%profile}} ON (torg_debtor.manager_id=profile.parent_id AND profile.model='. IntCode::BANKRUPT .')
                    WHERE torg.id=torg_debtor.torg_id
                ) AS bankrupt_person,
                (
                    SELECT organization.title FROM {{%torg_drawish}}
                    INNER JOIN {{%organization}} ON (torg_drawish.manager_id=organization.parent_id AND organization.model='. IntCode::MANAGER .')
                    WHERE torg.id=torg_drawish.torg_id
                ) AS other_company,
                (
                    SELECT CONCAT(profile.first_name, profile.last_name) FROM {{%torg_drawish}}
                    INNER JOIN {{%profile}} ON (torg_drawish.manager_id=profile.parent_id AND profile.model='. IntCode::MANAGER .')
                    WHERE torg.id=torg_drawish.torg_id
                ) AS other_person'
            )
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
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'msg_id', $this->msg_id])
            ->andFilterWhere(['property' => $this->property])
            ->andFilterWhere(['offer' => $this->offer]);
        
        return $dataProvider;
    }
}
