<?php

namespace frontend\modules\models;

use common\models\db\Organization;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\Sro;

/**
 * SroSearch represents the model behind the search form of `common\models\db\Sro`.
 */
class SroSearch extends Sro
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
        $limit = \Yii::$app->params['defaultPageLimit'];
        $query = Sro::find();

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

        $query->joinWith(['organizationRel']);

        if ($this->search) {
            $query->andFilterWhere(['ilike', Organization::tableName() . '.title', $this->search]);
        }

        $query->orderBy([Organization::tableName() . '.title' => SORT_ASC]);

        $queryForCount = clone $query;
        $this->totalCount = $queryForCount->count();

        $query->offset($this->offset)
            ->limit($limit);

        return $dataProvider;
    }

    public function getTotalCount()
    {
        return $this->totalCount;
    }
}
