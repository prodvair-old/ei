<?php

namespace frontend\modules\models;

use common\models\db\Etp;
use common\models\db\Organization;
use common\models\db\Place;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Expression;
use yii\db\Query;
use common\models\db\Torg;
use common\models\db\Lot;

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

    public $search;

    public $sortBy;

    public $count;

    public $region;

    public $haveImage;

    public $offset;

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
            [['id', 'torg_id', 'step_measure', 'deposit_measure', 'status', 'reason', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'minPrice', 'maxPrice', 'mainCategory', 'type',
                'subCategory', 'etp', 'owner', 'tradeType', 'search', 'sortBy', 'haveImage', 'region', 'offset'], 'safe'],
            [['start_price', 'step', 'deposit'], 'number'],
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
        $query = Lot::find()
//            ->select(['lot.id', 'lot.title', 'lot.start_price', 'torg.published_at']);
            ->select(['lot.*', 'torg.published_at']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => false,
            'pagination' => false,
        ]);

        $this->load($params);

        if ($this->etp) {
            $query->joinWith(['torg', 'torg.etp', 'torg.owner', 'categories', 'place']);
            $query->andFilterWhere(['IN', Organization::tableName() . '.id', $this->etp]);

        } else {
            $query->joinWith(['torg', 'torg.owner', 'categories', 'place']);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['>=', 'start_price', $this->minPrice])
            ->andFilterWhere(['<=', 'start_price', $this->maxPrice]);


        if ($this->subCategory) {
            $query->andFilterWhere(['IN', Category::tableName() . '.id', $this->subCategory]);
        }

        if ($this->region) {
            $query->andFilterWhere(['IN', Place::tableName() . '.region_id', $this->region]);
        }

        if ($this->mainCategory) {
            $subCategories = Category::findOne(['id' => $this->mainCategory]);
            $leaves = $subCategories->leaves()->all();
            $allCategories[] = $this->mainCategory;
            foreach ($leaves as $leaf) {
                $allCategories[] = $leaf->id;
            }

            $query->andFilterWhere(['IN', Category::tableName() . '.id', $allCategories]);
        }

        if ($this->type != 0) {
            $query->andFilterWhere(['=', Torg::tableName() . '.property', $this->type]);
        }

        $query->andFilterWhere(['IN', Torg::tableName() . '.offer', $this->tradeType]);

        if ($this->search) {
            $query->addSelect(new Expression("ts_rank({{%lot}}.fts,plainto_tsquery('ru', :q)) as rank"));
            $query->andWhere(new Expression("{{%lot}}.fts  @@ plainto_tsquery('ru', :q)", [':q' => $this->search]));
            $query->addOrderBy(['rank' => SORT_DESC]);
        }


        if ($this->haveImage) {
            $query->rightJoin(Onefile::tableName(), 'onefile.parent_id = lot.id AND onefile.model = :lot AND onefile.name IS NOT NULL', ['lot' => Lot::className()]);
        }


        if ($this->sortBy) {
            switch ($this->sortBy) {
                case self::NAME_DESC:
                    $query->addOrderBy(['lot.title' => SORT_DESC]);
                    break;

                case self::NAME_ASC:
                    $query->addOrderBy(['lot.title' => SORT_ASC]);
                    break;

                case self::DATE_DESC:
                    $query->addOrderBy(['torg.published_at' => SORT_DESC]);
                    break;

                case self::DATE_ASC:
                    $query->addOrderBy(['torg.published_at' => SORT_ASC]);
                    break;

                case self::PRICE_DESC:
                    $query->addOrderBy(['lot.start_price' => SORT_DESC]);
                    break;

                case self::PRICE_ASC:
                    $query->addOrderBy(['lot.start_price' => SORT_ASC]);
                    break;
            }
        } else {
            $query->addOrderBy(['torg.published_at' => SORT_DESC]);
//            $query->addOrderBy(['lot.id' => SORT_DESC]);
        }

//        $this->count = $query->count();
//        $this->count = $query->count("lot.id");
//        $this->count = $query->count("DISTINCT lot.id");
//        $query->groupBy(['lot.id', 'torg.published_at']);

//        $this->pages = new Pagination(['totalCount' => $this->count, 'defaultPageSize' => 10, 'pageSize' => 10]);
//        $this->pages = new Pagination(['defaultPageSize' => 10, 'pageSize' => 10]);
        $query->offset($this->offset)
            ->limit(15);

//        echo "<pre>";
//        var_dump($query->createCommand()->getRawSql());
//        echo "</pre>";
//        die;

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
