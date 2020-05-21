<?php

namespace frontend\modules\models;

use common\models\db\Organization;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\models\Lot;
use yii\data\Pagination;
use yii\db\Expression;
use yii\db\Query;

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

    public $search;

    public $sortBy;

    public $count;

    const NAME_DESC = 'nameDESC',
        NAME_ASC = 'nameASC',
        DATE_DESC = 'dateDESC',
        DATE_ASC = 'dateASC',
        PRICE_DESC = 'priceDESC',
        PRICE_ASC = 'priceASC';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'torg_id', 'step_measure', 'deposite_measure', 'status', 'reason', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'minPrice', 'maxPrice', 'mainCategory', 'type',
                'subCategory', 'etp', 'owner', 'tradeType', 'search', 'sortBy'], 'safe'],
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
            'query'      => $query,
//            'totalCount' => $query->count(1),
//            'sort'  => [
//                'defaultOrder' => [
////                    'id' => SORT_DESC,
//                ]
//            ],
            'sort'       => false,
            'pagination' => false,
//            'pagination' => [
//                'pageSize' => 2,
//            ]
        ]);

//        $dataProvider->setTotalCount(15);

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


        if ($this->subCategory) {
            $query->andFilterWhere(['IN', Category::tableName() . '.id', $this->subCategory]);
        }

        if ($this->mainCategory) {
            $subCategories = Category::findOne(['id' => $this->mainCategory]);
            $leaves = $subCategories->leaves()->all();
            $allCategories[] = $this->mainCategory;
            foreach ($leaves as $leaf) {
                $allCategories[] = $leaf->id;
            }
//            $query->andFilterWhere(['=', Category::tableName() . '.id', $this->mainCategory]);
            $query->andFilterWhere(['IN', Category::tableName() . '.id', $allCategories]);
        }

        if ($this->type != 0) {
            $query->andFilterWhere(['=', Torg::tableName() . '.property', $this->type]);
        }

        $query->andFilterWhere(['IN', Torg::tableName() . '.offer', $this->tradeType]);

        $query->andFilterWhere(['IN', Organization::tableName() . '.id', $this->etp]);
        $query->andFilterWhere(['IN', 'owner' . '.id', $this->owner]);


        $query->addSelect('lot.*');

        if ($this->search) {
            $query->addSelect(new Expression("ts_rank({{%lot}}.fts,plainto_tsquery('ru', :q)) as rank"));
            $query->andWhere(new Expression("{{%lot}}.fts  @@ plainto_tsquery('ru', :q)", [':q' => $this->search]));
            $query->addOrderBy(['rank' => SORT_DESC]);
        }

        if ($this->sortBy) {
            switch ($this->sortBy) {
                case self::NAME_DESC:
                    $query->addOrderBy(['title' => SORT_DESC]);
                    break;

                case self::NAME_ASC:
                    $query->addOrderBy(['title' => SORT_ASC]);
                    break;

                case self::DATE_DESC:
                    $query->addOrderBy(['torg.published_at' => SORT_DESC]);
                    break;

                case self::DATE_ASC:
                    $query->addOrderBy(['torg.published_at' => SORT_ASC]);
                    break;

                case self::PRICE_DESC:
                    $query->addOrderBy(['start_price' => SORT_DESC]);
                    break;

                case self::PRICE_ASC:
                    $query->addOrderBy(['start_price' => SORT_ASC]);
                    break;
            }
        }
        else {
            $query->addOrderBy(['torg.published_at' => SORT_DESC]);
        }

        $this->count = $query->count();
        $this->pages = new Pagination(['totalCount' => $this->count]);
        $query->offset($this->pages->offset)
            ->limit(10);

        return $dataProvider;
    }

    /**
     * @return array
     */
    public function getSortMap(): array
    {
        return [
            self::DATE_DESC  => 'Сначала новые',
            self::DATE_ASC   => 'Сначала старые',
            self::NAME_ASC   => 'Название от А до Я',
            self::NAME_DESC  => 'Название от Я до А',
            self::PRICE_DESC => 'Цена по убыванию',
            self::PRICE_ASC  => 'Цена по возрастанию'
        ];
    }

    public function findSuggest(string $query): array
    {
        $tQuery = (new Query())->from('{{%organization}}')
            ->select([
                '{{%organization}}.id',
                '{{%organization}}.title',
                '{{%organization}}.full_title',
                new Expression("ts_rank({{%organization}}.fts,plainto_tsquery('ru', :q)) as rank"),
            ])
            ->where(new Expression("{{%organization}}.fts  @@ plainto_tsquery('ru', :q)", [':q' => $query]))
            ->limit(10)
            ->orderBy(['rank' => SORT_DESC]);

        return $tQuery->all();
    }

}
