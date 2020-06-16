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

    public $andArchived;

    public $efrsb;

    public $bankruptName;

    public $void;

    public $startApplication;

    public $competedApplication;

    public $torgStartDate;

    public $torgEndDate;

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
            [['id', 'torg_id', 'step_measure', 'deposit_measure', 'status', 'reason', 'created_at', 'updated_at'],
                'integer'],
            [['title', 'description', 'minPrice', 'maxPrice', 'mainCategory', 'type',
                'subCategory', 'etp', 'owner', 'tradeType', 'search', 'sortBy', 'haveImage', 'region',
                'offset', 'efrsb', 'bankruptName', 'torgStartDate', 'torgEndDate', 'andArchived',
                'startApplication', 'competedApplication'], 'safe'],
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
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        $query = Lot::find()
            ->select(['lot.*', 'torg.published_at']);

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

        $query->joinWith(['torg', 'categories']);

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
            $query->joinWith(['torg.etp']);
            $query->andFilterWhere(['IN', Organization::tableName() . '.id', $this->etp]);
        }

        if ($this->type == Torg::PROPERTY_BANKRUPT) {
            $query->joinWith(['torg.bankrupt']);
        } elseif ($this->type == Torg::PROPERTY_ZALOG) {
            $query->joinWith(['torg.owner']);
        }

        if ($this->owner) {
            $query->andFilterWhere(['IN', Organization::tableName() . '.id', $this->owner]);
        }
        
        if ($this->bankruptName) {
            $fullName = explode(' ', $this->bankruptName);
            $query->joinWith(['torg.bankruptProfile']);
            $query->andFilterWhere(['=', Profile::tableName() . '.last_name', $fullName[ 0 ]]);
            $query->andFilterWhere(['=', Profile::tableName() . '.first_name', $fullName[ 1 ]]);
            $query->andFilterWhere(['=', Profile::tableName() . '.middle_name', $fullName[ 2 ]]);
        }

        if ($this->region) {
            $query->joinWith(['place']);
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

        if ($this->torgStartDate) {
            $this->torgStartDate = \Yii::$app->formatter->asTimestamp($this->torgStartDate);
        }
        if ($this->torgEndDate) {
            $this->torgEndDate = \Yii::$app->formatter->asTimestamp($this->torgEndDate);
        }

        $query->andFilterWhere(['IN', Torg::tableName() . '.offer', $this->tradeType]);
        $query->andFilterWhere(['BETWEEN', Torg::tableName() . '.started_at', $this->torgStartDate, $this->torgEndDate]);

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
        }

        $query->offset($this->offset)
            ->limit(15);

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
}
