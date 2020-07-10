<?php

namespace frontend\modules\models;

use common\models\db\Organization;
use common\models\db\Profile;
use common\models\db\Torg;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\Bankrupt;
use yii\db\conditions\AndCondition;
use yii\db\conditions\LikeCondition;

/**
 * BankruptSearch represents the model behind the search form of `common\models\db\Bankrupt`.
 */
class BankruptSearch extends Bankrupt
{

    public $search;

    public $offset;

    public $torgsIsActive;

    private $totalCount;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['search', 'torgsIsActive', 'offset'], 'safe'],
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
        $query = Bankrupt::find();

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

        $query->joinWith(['profileRel', 'organizationRel']);

        $search = null;

        if ($this->search) {
            $search = trim($this->search, ' ');
            $searchSafe = preg_replace('/\s+/', ' ', $search);

            $search = (int)$search;

            if ($search !== 0) {
                if (strlen($searchSafe) === 10) {
                    $query->andFilterWhere(['=', Organization::tableName() . '.inn', $searchSafe]);
                } else if (strlen($searchSafe) === 12) {
                    $query->andFilterWhere(['=', Profile::tableName() . '.inn', $searchSafe]);
                }
            } else {
                $fullName = explode(' ', $searchSafe);
                $query->orFilterWhere(['ilike', Organization::tableName() . '.title', $searchSafe]);

                $condition = null;
                if (isset($fullName[ 0 ])) {
                    $condition[] = new LikeCondition(Profile::tableName() . '.last_name', 'ilike', $fullName[ 0 ]);
                }

                if (isset($fullName[ 1 ])) {
                    $condition[] = new LikeCondition(Profile::tableName() . '.first_name', 'ilike', $fullName[ 1 ]);
                }

                if (isset($fullName[ 2 ])) {
                    $condition[] = new LikeCondition(Profile::tableName() . '.middle_name', 'ilike', $fullName[ 2 ]);
                }

                $query->orWhere(new AndCondition($condition));
            }
        }

        if($this->torgsIsActive) {
            $query->joinWith(['torg']);
            $query->andFilterWhere(['>', Torg::tableName() . '.completed_at', time()]);
        }

        $query->orderBy([
            Organization::tableName() . '.title'  => SORT_ASC,
            Profile::tableName() . '.last_name'   => SORT_ASC,
            Profile::tableName() . '.first_name'  => SORT_ASC,
            Profile::tableName() . '.middle_name' => SORT_ASC
        ]);

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
