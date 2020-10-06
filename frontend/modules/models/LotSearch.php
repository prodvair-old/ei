<?php

namespace frontend\modules\models;

use common\models\db\Etp;
use common\models\db\Organization;
use common\models\db\Place;
use common\models\db\Profile;
use common\models\db\Report;
use DateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use common\models\db\Torg;
use common\models\db\Lot;
use common\models\db\LotPrice;

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

    public $publishedDate;

    public $torgDateRange;

    public $hasReport;

    public $priceDown;

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
                'offset', 'efrsb', 'bankruptName', 'publishedDate', 'andArchived',
                'startApplication', 'competedApplication', 'torgDateRange', 'hasReport', 'priceDown'], 'safe'],
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
    public function search($params = null)
    {
        $limit = \Yii::$app->params[ 'defaultPageLimit' ];
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

        $query->joinWith(['torg','region'], true, 'INNER JOIN');
        $query->joinWith(['categories']);

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

        if($this->type == Torg::PROPERTY_ZALOG) {
            $query->joinWith(['torg.owner']);
        }

        if ($this->owner) {
            $query->andFilterWhere(['IN', Organization::tableName() . '.id', $this->owner]);
        }

        if ($this->bankruptName) {
            $fullName = explode(' ', $this->bankruptName);
            $query->joinWith(['torg.bankruptProfile'], true, 'INNER JOIN');
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

        if ($this->publishedDate) {
            $this->publishedDate = \Yii::$app->formatter->asTimestamp($this->publishedDate);
        }

        if ($this->torgDateRange) {
            $datetime = explode(' - ', $this->torgDateRange);
            $datetime[ 0 ] = \Yii::$app->formatter->asTimestamp($datetime[ 0 ]);
            $datetime[ 1 ] = \Yii::$app->formatter->asTimestamp($datetime[ 1 ]);
            $query->andFilterWhere(['BETWEEN', Torg::tableName() . '.started_at', $datetime[ 0 ], $datetime[ 1 ]]);
        }

        $query->andFilterWhere(['>=', Torg::tableName() . '.published_at', $this->publishedDate]);
        $query->andFilterWhere(['IN', Torg::tableName() . '.offer', $this->tradeType]);

        if ($this->publishedDate) { //TODO
            $this->publishedDate = \Yii::$app->formatter->asDate($this->publishedDate, 'long');
        }

        if ($this->search) {
            $query->addSelect(new Expression("ts_rank({{%lot}}.fts,plainto_tsquery('ru', :q)) as rank"));
            $query->andWhere(new Expression("{{%lot}}.fts  @@ plainto_tsquery('ru', :q)", [':q' => $this->search]));
            $query->addOrderBy(['rank' => SORT_DESC]);
        }

        if ($this->haveImage) {
            $query->innerJoin(Onefile::tableName(), 'onefile.parent_id = lot.id AND onefile.model = :lot AND onefile.name IS NOT NULL', ['lot' => Lot::className()]);
        }

        if ($this->hasReport) {
            $query->innerJoin(Report::tableName(), 'report.lot_id = lot.id');
        }

        if ($this->priceDown) {
            $query->rightJoin(LotPrice::tableName(), LotPrice::tableName() . '.lot_id = ' . Lot::tableName() . '.id');
            $today = new \DateTime();
            $query->andFilterWhere([
                'and',
                ['<=', LotPrice::tableName() . '.started_at', \Yii::$app->formatter->asTimestamp($today)],
                ['>=', LotPrice::tableName() . '.end_at', \Yii::$app->formatter->asTimestamp($today)]
            ]);
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

//        $query->groupBy('lot.id, torg.published_at');

        $query->offset($this->offset)
            ->cache(3600 * 24)
            ->limit($limit)
            ;

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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotWithReportsQuery()
    {
        return Lot::find()
            ->joinWith(['torg', 'report'], true, 'INNER JOIN')
            ->where(['!=', Lot::tableName() . '.status', Lot::STATUS_COMPLETED])
            ->andWhere(['>', Torg::tableName() . '.end_at', time()])
            ->groupBy([
                Lot::tableName() . '.id',
                Torg::tableName() . '.published_at',
            ])
            ->orderBy(['count(' . Report::tableName() . '.id)' => SORT_DESC, Torg::tableName() . '.published_at' => SORT_DESC])
            ->cache(3600 * 24);
    }


    /**
     * @return array|ActiveRecord[]
     */
    public function getLotWithReports()
    {
        return $this->getLotWithReportsQuery()
            ->limit(7)
            ->all();
    }

    /**
     * @return int|string
     */
    public function getLotWithReportsTotalCount()
    {
        return $this->getLotWithReportsQuery()
            ->count('lot.id');
    }

    /**
     * @return array|ActiveRecord[]
     * @throws \Exception
     */
    public function getLotWithPriceDown()
    {
        $today = new DateTime();

        return Lot::find()->joinWith(['torg'], true, 'INNER JOIN')
            ->rightJoin(LotPrice::tableName(), LotPrice::tableName() . '.lot_id = ' . Lot::tableName() . '.id')
            ->where([
                'and',
                ['!=', Lot::tableName() . '.status', Lot::STATUS_COMPLETED],
                ['>', Torg::tableName() . '.end_at', time()],
                ['<=', LotPrice::tableName() . '.started_at', \Yii::$app->formatter->asTimestamp($today)],
                ['>=', LotPrice::tableName() . '.end_at', \Yii::$app->formatter->asTimestamp($today)]
            ])
            ->orderBy([LotPrice::tableName() . '.id' => SORT_DESC, Torg::tableName() . '.published_at' => SORT_DESC])
            ->limit(7)
            ->cache(3600 * 24)
            ->all();
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getLotLowPrice()
    {
        return Lot::find()->joinWith(['torg'], true, 'INNER JOIN')
            ->where([
                'and',
                ['!=', Lot::tableName() . '.status', Lot::STATUS_COMPLETED],
                ['>', Torg::tableName() . '.end_at', time()],
                ['<', Lot::tableName() . '.start_price', 100],
            ])
            ->orderBy([Torg::tableName() . '.published_at' => SORT_DESC])
            ->limit(3)
            ->cache(3600 * 24)
            ->all();
    }


    /**
     * @return array|ActiveRecord[]
     */
    public function getLotWithCheapRealEstate()
    {
        $subCategories = Category::findOne(['id' => 2]);
        $leaves = $subCategories->leaves()->all();
        $allCategories[] = 2;
        foreach ($leaves as $leaf) {
            $allCategories[] = $leaf->id;
        }

        return Lot::find()->joinWith(['torg', 'categories'])
            ->where([
                'and',
                ['!=', Lot::tableName() . '.status', Lot::STATUS_COMPLETED],
                ['>', Torg::tableName() . '.end_at', time()],
            ])
            ->andWhere(['IN', Category::tableName() . '.id', $allCategories])
            ->orderBy([Lot::tableName() . '.start_price' => SORT_ASC])
            ->limit(7)
            ->cache(3600 * 24)
            ->all();
    }

    /**
     * @return array|ActiveRecord[]
     * @throws \Exception
     */
    public function getLotWithEndedTorg()
    {
        $today = new DateTime();
        return Lot::find()->joinWith(['torg'], true, 'INNER JOIN')
            ->andWhere(['<=', Torg::tableName() . '.end_at', \Yii::$app->formatter->asTimestamp($today)])
            ->orderBy([Torg::tableName() . '.end_at' => SORT_DESC])
            ->limit(3)
            ->cache(3600 * 24)
            ->all();
    }

    /**
     * @param $type
     * @return int|string
     */
    public function getLotTotalCountByType($type)
    {
        switch ($type) {
            case 'lotTotalCount' :
                return Lot::find()->count('id');
            case 'lotWithReportsTotalCount' :
                return $this->getLotWithReportsTotalCount();
            default:
                return 0;
                break;
        }
    }
}