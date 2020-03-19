<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;

use common\models\Query\LotsCategory;
use common\models\Query\LotsSubCategory;
use common\models\Query\Regions;
use common\models\Query\Lot\Lots;
/*
 * Search Lot
 */

class SearchLot extends Model
{
    public $type;
    public $category;
    public $subCategory;
    public $region;
    public $etp;
    public $minPrice;
    public $maxPrice;
    public $imageCheck;
    public $archivCheck;
    public $tradeType;
    public $owners;
    public $search;
    public $sortBy;
    


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'category', 'search', 'sortBy'], 'string'],
            [['subCategory', 'region', 'tradeType', 'etp', 'owners'], 'default'],
            [['minPrice', 'maxPrice'], 'number'],
            [['imageCheck', 'archivCheck'], 'boolean'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function searchBy($url = null, $type = null, $sortBy = null)
    {

        if (!empty($this->archivCheck)) {
            if (!$this->archivCheck) {
                $lots = Lots::isActive();
            }
            $lots = Lots::isArchive();
        } else {
            $lots = Lots::isActive();
        }

        $lots->alias('lot')->joinWith(['categorys', 'torg', 'thisPriceHistorys']);
        
        $lotsPrice = Clone $lots;

        if (!empty($category) && $category != 'all' && $category != 'lot-list' && empty($this->category) && $this->category != 0) {
            $this->category = $category;
        }

        $checkUrl = false;

        $where = ['and'];
        $where[] = ['published' => true];
        $having = '';
        $sort = [];

        if ($sortBy) {
            foreach ($sortBy as $key => $value) {
                $sort[$key] = $value;
            }
        }

        if (!empty($this->etp) || !empty($this->owners)) {
            $orWhere = ['or'];

            if (!empty($this->etp)) {
                if (count($this->etp) == 1) {
                    $etp = '"torg"."etpId" = '.$this->etp[0];
                } else {
                    foreach ($this->etp as $key => $value) {
                        if ($key != 0) {
                            $etp .= ' OR ';
                        }
                        $etp .= ' "torg"."etpId" = '.$value;
                    }
                }
                
                $orWhere[] = 'CASE WHEN "torg"."typeId" = 1
                    THEN '.$etp.'
                END';
            }
            if (!empty($this->owners)) {
                if (count($this->owners) == 1) {
                    $owners = '"torg"."ownerId" = '.$this->owners[0];
                } else {
                    foreach ($this->owners as $key => $value) {
                        if ($key != 0) {
                            $owners .= ' OR ';
                        }
                        $owners .= ' "torg"."ownerId" = '.$value;
                    }
                }

                $orWhere[] = 'CASE WHEN "torg"."typeId" = 3
                    THEN '.$owners.'
                END';
            }

            $where[] =$orWhere;
        }
        

        if (!empty($this->type)) {
            if ($this->type !== 'all') {
                $where[] = ['torg.type' => $this->type];
            }
        }

        if (!empty($this->search)) {
            $where[] = [
                'or',
                'to_tsvector(lot.description) @@ plainto_tsquery(\''.pg_escape_string($this->search).'\')',
                ['like', 'LOWER(lot.description)',pg_escape_string(mb_strtolower($this->search, 'UTF-8'))],
            ];
            $sort['ts_rank(to_tsvector(lot.description), plainto_tsquery(\''.pg_escape_string($this->search).'\'))'] = SORT_ASC;
        }


        if ($this->category == '0') {
            $url = $this->type.'/lot-list';
        } else if (!empty($this->category) && $this->category != 'all' && $this->category != '0') {
            $category = LotsCategory::findOne($this->category);
            $url = $this->type.'/'.$category->translit_name;

            if (empty($this->subCategory) || $this->subCategory == 'all' || $this->subCategory == '0') {
                $orWhere = ['or'];
                foreach (LotsSubCategory::find()->where(['categoryId' => $this->category])->all() as $key => $subCategory) {
                    $otherCategoryBankrupt = '';
                    $otherCategoryArrest = '';

                    if (!empty($subCategory->bankruptCategorys)) {
                        foreach ($subCategory->bankruptCategorys as $key => $value) {
                            if ($key != 0) {
                                $otherCategoryBankrupt .= ' OR ';
                            }
                            $otherCategoryBankrupt .= ' "categorys"."categoryId" = '.$value;
                        }
                        $orWhere[] = 'CASE WHEN "torg"."typeId" = 1
                                THEN '.$otherCategoryBankrupt.'
                            END';
                    }
                    if (!empty($subCategory->arrestCategorys)) {
                        foreach ($subCategory->arrestCategorys as $key => $value) {
                            if ($key != 0) {
                                $otherCategoryArrest .= ' OR ';
                            }
                            $otherCategoryArrest .= ' "categorys"."categoryId" = '.$value;
                        }
                        $orWhere[] = 'CASE WHEN "torg"."typeId" = 2
                                THEN '.$otherCategoryArrest.'
                            END';
                    }
                    $orWhere[] = 'CASE WHEN "torg"."typeId" = 3
                            THEN "categorys"."categoryId" = '.$subCategory->id.' END';
                }
                $where[] = $orWhere;
            } else if ($this->subCategory != '0'){
                $orWhere = ['or'];
                foreach ($this->subCategory as $keySubCategory => $subCategoryId) {
                    $subCategory = LotsSubCategory::findOne(['id' => $subCategoryId]);

                    if ($keySubCategory == 0) {
                        $url .= '/'.$subCategory->nameTranslit;
                        $checkUrl = true;
                    }

                    $otherCategoryBankrupt = '';
                    $otherCategoryArrest = '';

                    if (!empty($subCategory->bankruptCategorys)) {
                        foreach ($subCategory->bankruptCategorys as $key => $value) {
                            if ($key != 0) {
                                $otherCategoryBankrupt .= ' OR ';
                            }
                            $otherCategoryBankrupt .= ' "categorys"."categoryId" = '.$value;
                        }
                        $orWhere[] = 'CASE WHEN "torg"."typeId" = 1
                                THEN '.$otherCategoryBankrupt.'
                            END';
                    }
                    if (!empty($subCategory->arrestCategorys)) {
                        foreach ($subCategory->arrestCategorys as $key => $value) {
                            if ($key != 0) {
                                $otherCategoryArrest .= ' OR ';
                            }
                            $otherCategoryArrest .= ' "categorys"."categoryId" = '.$value;
                        }
                        $orWhere[] = 'CASE WHEN "torg"."typeId" = 2
                                THEN '.$otherCategoryArrest.'
                            END';
                    }
                    $orWhere[] = 'CASE WHEN "torg"."typeId" = 3
                            THEN "categorys"."categoryId" = '.$subCategory->id.' END';

                }
                $where[] = $orWhere;
            }
        }
        if (!empty($this->region)) {
            $addresSearchCheck = true;
            if (is_array($this->region)) {
                if (count($this->region) == 1) {
                    if ($this->region[0] != 0) {
                        $regionInfo = Regions::findOne($this->region[0]);

                        if ($checkUrl) {
                            $url .= '/'.$regionInfo->name_translit;
                        }
                        
                        $where[] = ['regionId'=>$this->region[0]];
                    }
                } else {
                    $orWhere = ['or'];
                    foreach ($this->region as $key => $region) {
                        if ($region != 0) {
                            $regionInfo = Regions::findOne($value);

                            if ($key == 0 && $checkUrl) {
                                $url .= '/'.$regionInfo->name_translit;
                            }

                            $orWhere[] = ['regionId'=>$region];
                        }
                    }
                    $where[] = $orWhere;
                }
            } else {
                if ($this->region == 0) {
                    $this->region = null;
                } else {
                    $regionInfo = Regions::findOne($this->region);

                    if ($checkUrl) {
                        $url .= '/'.$regionInfo->name_translit;
                    }

                    $where[] = ['regionId'=>$this->region];
                }
            }
        }
        if (!empty($this->tradeType)) {
            if (count($this->tradeType) == 1) {
                $where[] = ['torg.tradeTypeId'=>$this->tradeType[0]];
            } else {
                $where[] = [
                    'or',
                    ['torg.tradeTypeId'=>$this->tradeType[0]],
                    ['torg.tradeTypeId'=>$this->tradeType[1]]
                ];
            }
        }
        if (!empty($this->imageCheck)) {
            $where[] = ['not', ['images' => null]];
        }

        $lots->where($where);
        
        if (!empty($this->minPrice)) {
            $lots->andWhere('CASE WHEN "thisPriceHistorys".price IS NOT NULL
                    THEN "thisPriceHistorys".price >= '.$this->minPrice.'
                    ELSE lot."startPrice" >= '.$this->minPrice.'
                END');
        }
        if (!empty($this->maxPrice)) {
            $lots->andWhere('CASE WHEN "thisPriceHistorys".price IS NOT NULL
                    THEN "thisPriceHistorys".price <= '.$this->maxPrice.'
                    ELSE lot."startPrice" <= '.$this->maxPrice.'
                END');
        }
        
        return ['lots'=>$lots->orderBy($sort), 'lotsPrice'=>$lotsPrice->where($where), 'url'=>$url];
    }
}