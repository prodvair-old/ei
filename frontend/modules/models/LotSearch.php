<?php

namespace frontend\modules\models;

use common\models\db\Organization;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\models\Lot;
use yii\data\Pagination;

/**
 * LotSearch represents the model behind the search form of `frontend\modules\models\Lot`.
 */
class LotSearch extends Lot
{
    public $pages;

    public $minPrice;

    public $maxPrice;

    public $mainCategory;

    public $type;

    public $subCategory;

    public $etp;

    public $owner;

    public $tradeType;

    public $deposite_measure;
    public $deposite;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'torg_id', 'step_measure', 'deposite_measure', 'status', 'reason', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'minPrice', 'maxPrice', 'mainCategory', 'type',
                'subCategory', 'etp', 'owner', 'tradeType'], 'safe'],
            [['start_price', 'step', 'deposite'], 'number'],
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
        $query = Lot::find();
        $limit = 2;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
//            'pagination' => [
//                'pageSize' => 2,
//            ]
        ]);

//        $query->joinWith(['torg', 'torg.torgPledge', 'torg.torgPledge.owner', 'category', 'category.category']);
//        $query->joinWith(['torg', 'torg.etp', 'torg.owner', 'category', 'category.category']);
        $query->joinWith(['torg', 'torg.etp', 'torg.owner owner', 'category', 'category.category']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'torg_id' => $this->torg_id,
//            'start_price' => $this->start_price,
//            'step' => $this->step,
//            'step_measure' => $this->step_measure,
//            'deposite' => $this->deposite,
//            'deposite_measure' => $this->deposite_measure,
//            'status' => $this->status,
//            'reason' => $this->reason,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//        ]);

//        echo "<pre>";
//        var_dump($this->category);
//        echo "</pre>";
//        die;

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['>=', 'start_price', $this->minPrice])
            ->andFilterWhere(['<=', 'start_price', $this->maxPrice]);


        if ($this->subCategory !== null) {
            $query->andFilterWhere(['in', Category::tableName() . '.id', $this->subCategory]);
        } elseif ($this->mainCategory != 0) {
            $query->andFilterWhere(['=', Category::tableName() . '.id', $this->mainCategory]);
        }


        if ($this->type != 0) {
            $query->andFilterWhere(['=', Torg::tableName() . '.property', $this->type]);
        }

        $query->andFilterWhere(['IN', Torg::tableName() . '.offer', $this->tradeType]);

        $query->andFilterWhere(['IN', Organization::tableName() . '.id', $this->etp]);
        $query->andFilterWhere(['IN', 'owner' . '.id', $this->owner]);


//        $totalCount = $dataProvider->getTotalCount();

//        $limit = ($totalCount <= $limit) ? $totalCount : $limit;

//        $countQuery = clone $query;
//        $this->pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 1, 'defaultPageSize' => 1]);
//        $query->offset($this->pages->offset)
//            ->limit($this->pages->limit);

        return $dataProvider;
    }
}
