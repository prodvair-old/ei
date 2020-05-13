<?php

namespace frontend\modules\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\models\LotsOld;

/**
 * LotsOldSearch represents the model behind the search form of `frontend\modules\models\LotsOld`.
 */
class LotsOldSearch extends LotsOld
{
    public $category;

    public $minPrice;

    public $maxPrice;

    public function rules()
    {
        return [
            [['id', 'torgId', 'lotNumber', 'stepTypeId', 'depositTypeId', 'regionId', 'oldId', 'bankId', 'archive'], 'integer'],
            [['msgId', 'createdAt', 'updatedAt', 'title', 'description', 'stepType', 'depositType', 'status', 'info', 'images', 'city', 'district', 'address'], 'safe'],
            [['startPrice', 'step', 'deposit'], 'number'],
            [['published'], 'boolean'],
            [['category', 'minPrice', 'maxPrice'], 'safe'],
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
        $query = LotsOld::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
//            'pagination' => [
//                'pageSize' => $pageSize,
//            ]
        ]);

        $query->joinWith(['torg', 'category', 'priceHistory'], true, 'INNER JOIN');
//        $query->joinWith(['torg', 'category'], true, 'INNER JOIN');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'            => $this->id,
            'torgId'        => $this->torgId,
            'lotNumber'     => $this->lotNumber,
            'createdAt'     => $this->createdAt,
            'updatedAt'     => $this->updatedAt,
//            'startPrice' => $this->startPrice,
            'step'          => $this->step,
            'stepTypeId'    => $this->stepTypeId,
            'deposit'       => $this->deposit,
            'depositTypeId' => $this->depositTypeId,
            'published'     => $this->published,
            'regionId'      => $this->regionId,
            'oldId'         => $this->oldId,
            'bankId'        => $this->bankId,
            'archive'       => $this->archive,
        ]);

        $query->andFilterWhere(['ilike', 'msgId', $this->msgId])
            ->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'stepType', $this->stepType])
            ->andFilterWhere(['ilike', 'depositType', $this->depositType])
            ->andFilterWhere(['ilike', 'status', $this->status])
            ->andFilterWhere(['ilike', 'info', $this->info])
            ->andFilterWhere(['ilike', 'images', $this->images])
            ->andFilterWhere(['ilike', 'city', $this->city])
            ->andFilterWhere(['ilike', 'district', $this->district])
            ->andFilterWhere(['ilike', 'address', $this->address])
            ->andFilterWhere(['>=', 'startPrice', $this->startPrice])
            ->andFilterWhere(['=', LotCategories::tableName() . '.categoryId', $this->category])
//            ->andFilterWhere(['>=', LotPriceHistory::tableName() . '.price', $this->minPrice])
//            ->andFilterWhere(['<=', LotPriceHistory::tableName() . '.price', $this->maxPrice])

        ;

        return $dataProvider;
    }
}
