<?php

namespace frontend\modules\models;

use common\models\db\Place;
use common\models\db\Profile;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\Manager;

/**
 * ManagerSearch represents the model behind the search form of `common\models\db\Manager`.
 */
class ManagerSearch extends Manager
{

    public $search;

    public $offset;

    private $totalCount;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['search', 'offset'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Manager::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => false,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['profileRel', 'placeRel']);
//        $query->leftJoin(Profile::tableName(), 'profile.parent_id = manager.id AND profile.model = ' . Manager::INT_CODE);
//        $query->leftJoin(Place::tableName(), 'place.parent_id = manager.id AND place.model = ' . Manager::INT_CODE);

        $fullName = null;

        if($this->search) {
            $fullName = trim($this->search, ' ');
            $fullName = preg_replace('/\s+/', ' ', $fullName);
            $fullName = explode(' ', $fullName);

            if(isset($fullName[0])) {
                $query->andFilterWhere(['ilike', Profile::tableName() . '.last_name', $fullName[0]]);
            }

            if(isset($fullName[1])) {
                $query->andFilterWhere(['ilike', Profile::tableName() . '.first_name', $fullName[1]]);
            }

            if(isset($fullName[2])) {
                $query->andFilterWhere(['ilike', Profile::tableName() . '.middle_name', $fullName[2]]);
            }
        }

        // grid filtering conditions
        $query->andFilterWhere(['agent' => Manager::AGENT_PERSON]);

        $query->orderBy([
            Profile::tableName() . '.last_name' => SORT_ASC,
            Profile::tableName() . '.first_name' => SORT_ASC,
            Profile::tableName() . '.middle_name' => SORT_ASC
        ]);

        $queryForCount = clone $query;
        $this->totalCount = $queryForCount->count();

        $query->offset($this->offset)
            ->limit(15);

        return $dataProvider;
    }

    public function getTotalCount() {
        return $this->totalCount;
    }
}
