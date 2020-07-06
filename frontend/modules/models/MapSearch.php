<?php

namespace frontend\modules\models;

use common\models\db\Etp;
use common\models\db\Organization;
use common\models\db\Place;
use common\models\db\Profile;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use common\models\db\Torg;
use common\models\db\Lot;

/**
 * MapSearch represents the model behind the search form of `frontend\modules\models\Lot`.
 */
class MapSearch extends Place
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

    public $limit;

    public $andArchived;

    public $efrsb;

    public $bankruptName;

    public $void;

    public $startApplication;

    public $competedApplication;

    public $publishedDate;

    public $torgStartDate;

    public $torgEndDate;

    public $model_code;

    public $north_west_lat;

    public $north_west_lon;

    public $south_east_lat;

    public $south_east_lon;

    const LOT_INT_CODE = 6;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['north_west_lat', 'north_west_lon', 'south_east_lat', 'south_east_lon'],
                'number'],
            [['title', 'description', 'minPrice', 'maxPrice', 'mainCategory', 'type',
                'subCategory', 'etp', 'owner', 'tradeType', 'search', 'sortBy', 'haveImage', 'region',
                'offset', 'limit', 'efrsb', 'bankruptName', 'publishedDate', 'torgStartDate', 'torgEndDate', 
                'andArchived', 'startApplication', 'competedApplication'], 'safe'],
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
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        $query = Place::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => false,
            'pagination' => false,
        ]);

        parse_str($params['filter'], $filter);

        $this->load($filter);

        $this->north_west_lat    = $params['north_west_lat'];
        $this->north_west_lon    = $params['north_west_lon'];
        $this->south_east_lat    = $params['south_east_lat'];
        $this->south_east_lon    = $params['south_east_lon'];
        $this->limit             = $params['limit'];
        $this->offset            = $params['offset'];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['lot.torg', 'lot.categories']);

        $query->andFilterWhere([Place::tableName() . '.model' => self::LOT_INT_CODE]);

        if ($this->north_west_lat) {
            $query->andFilterWhere(['>=', 'geo_lat', $this->north_west_lat]);
        }

        if ($this->north_west_lon) {
            $query->andFilterWhere(['>=', 'geo_lon', $this->north_west_lon]);
        }

        if ($this->south_east_lat) {
            $query->andFilterWhere(['<=', 'geo_lat', $this->south_east_lat]);
        }

        if ($this->south_east_lon) {
            $query->andFilterWhere(['<=', 'geo_lon', $this->south_east_lon]);
        }

        if (!$this->andArchived) {
            $query->andFilterWhere(['!=', Lot::tableName() . '.status', Lot::STATUS_COMPLETED]);
            $query->andFilterWhere(['>', Torg::tableName() . '.end_at', time()]);
        }

        if ($this->startApplication) {
            $query->andFilterWhere(['=', Lot::tableName() . '.status', Lot::STATUS_IN_PROGRESS]);
            $query->andFilterWhere(['=', Lot::tableName() . '.reason', Lot::REASON_APPLICATION]);
        }

        if ($this->competedApplication) {
            $query->andFilterWhere(['=', Lot::tableName() . '.status', Lot::STATUS_COMPLETED]);
            $query->andFilterWhere(['=', Lot::tableName() . '.reason', Lot::REASON_APPLICATION]);
        }

        if ($this->etp) {
            $query->joinWith(['lot.torg.etp']);
            $query->andFilterWhere(['IN', Organization::tableName() . '.id', $this->etp]);
        }

        if ($this->type == Torg::PROPERTY_BANKRUPT) {
            $query->joinWith(['lot.torg.bankrupt']);
        } elseif ($this->type == Torg::PROPERTY_ZALOG) {
            $query->joinWith(['lot.torg.owner']);
        }

        if ($this->owner) {
            $query->andFilterWhere(['IN', Organization::tableName() . '.id', $this->owner]);
        }
        
        if ($this->bankruptName) {
            $fullName = explode(' ', $this->bankruptName);
            $query->joinWith(['lot.torg.bankruptProfile']);
            $query->andFilterWhere(['=', Profile::tableName() . '.last_name', $fullName[ 0 ]]);
            $query->andFilterWhere(['=', Profile::tableName() . '.first_name', $fullName[ 1 ]]);
            $query->andFilterWhere(['=', Profile::tableName() . '.middle_name', $fullName[ 2 ]]);
        }

        if ($this->region) {
            $query->andFilterWhere(['IN', Place::tableName() . '.region_id', $this->region]);
        }
        if ($this->efrsb) {
            $query->andFilterWhere(['ilike', Torg::tableName() . '.msg_id', $this->efrsb, false]);
        }

        $query->andFilterWhere(['>=', 'start_price', $this->minPrice])
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

            $query->andFilterWhere(['IN', Category::tableName() . '.id', $allCategories]);
        }

        if ($this->type != 0) {
            $query->andFilterWhere(['=', Torg::tableName() . '.property', $this->type]);
        }

        if ($this->publishedDate) {
            $this->publishedDate = \Yii::$app->formatter->asTimestamp($this->publishedDate);
        }

        if ($this->torgStartDate) {
            $this->torgStartDate = \Yii::$app->formatter->asTimestamp($this->torgStartDate);
        }
        if ($this->torgEndDate) {
            $this->torgEndDate = \Yii::$app->formatter->asTimestamp($this->torgEndDate);
        }

        $query->andFilterWhere(['>=', Torg::tableName() . '.published_at', $this->publishedDate]);
        $query->andFilterWhere(['IN', Torg::tableName() . '.offer', $this->tradeType]);
        $query->andFilterWhere(['BETWEEN', Torg::tableName() . '.started_at', $this->torgStartDate, $this->torgEndDate]);

        if ($this->publishedDate) { //TODO
            $this->publishedDate = \Yii::$app->formatter->asDate($this->publishedDate, 'long');
        }
        if ($this->torgStartDate) { //TODO
            $this->torgStartDate = \Yii::$app->formatter->asDate($this->torgStartDate, 'long');
        }
        if ($this->torgEndDate) { //TODO
            $this->torgEndDate = \Yii::$app->formatter->asDate($this->torgEndDate, 'long');
        }

        if ($this->search) {
            $query->addSelect(new Expression("ts_rank({{%lot}}.fts,plainto_tsquery('ru', :q)) as rank"));
            $query->andWhere(new Expression("{{%lot}}.fts  @@ plainto_tsquery('ru', :q)", [':q' => $this->search]));
            $query->addOrderBy(['rank' => SORT_DESC]);
        }

        if ($this->haveImage) {
            $query->rightJoin(Onefile::tableName(), 'onefile.parent_id = lot.id AND onefile.model = :lot AND onefile.name IS NOT NULL', ['lot' => Lot::className()]);
        }

        $query->addOrderBy(['torg.published_at' => SORT_DESC]);

        $query->offset($this->offset)
            ->limit($this->limit);

        return $dataProvider;
    }
}
