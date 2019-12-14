<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;

use common\models\Query\LotsCategory;
use common\models\Query\Regions;
/**
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
    public $search;
    


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'category', 'search'], 'string'],
            [['subCategory', 'region', 'tradeType', 'etp'], 'default'],
            [['minPrice', 'maxPrice'], 'number'],
            [['imageCheck', 'archivCheck'], 'boolean'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function search($lots, $url = null)
    {
        // if (!$this->validate()) {
        //     return null;
        // }

        if (!empty($type) && empty($this->type)) {
            $this->type = $type;
        }
        if (!empty($category) && $category != 'all' && $category != 'lot-list' && empty($this->category) && $this->category != 0) {
            $this->category = $category;
        }

        $checkUrl = false;

        $urlArray = explode('/', $url);

        if ($urlArray[0] != $this->type) {
            $url = $this->type;
        }
        
        switch ($this->type) {
            case 'bankrupt':
                    $where = ['and'];
                    $whereAnd = ['and'];
                    $addresSearchCheck = false;

                    if ($this->category == '0') {
                        $url = $this->type.'/lot-list';
                    } else if (!empty($this->category) && $this->category != 'all') {
                        $category = LotsCategory::findOne($this->category);
                        $url = $this->type.'/'.$category->translit_name;

                        if (empty($this->subCategory) || $this->subCategory == 'all' || $this->subCategory == '0') {
                            $orWhere = ['or'];
                            foreach ($category->bankrupt_categorys as $key => $value) {
                                $orWhere[] = ['category.lotclassifier'=>$key];
                            }
                            $where[] = $orWhere;
                        } else if ($this->subCategory != '0'){
                            if (count($this->subCategory) == 1) {
                                foreach ($category->bankrupt_categorys as $key => $value) {
                                    if ($this->subCategory[0] == $key) {
                                        $where[] = ['category.lotclassifier'=>$key];
                                        $url .= '/'.$value['translit'];
                                        $checkUrl = true;
                                    }
                                }
                            } else {
                                $orWhere = ['or'];
                                foreach ($this->subCategory as $keySubCategory => $subCategory) {
                                    foreach ($category->bankrupt_categorys as $key => $value) {
                                        if ($subCategory == $key) {
                                            $orWhere[] = ['category.lotclassifier'=>$key];
                                            if ($keySubCategory == 0) {
                                                $url .= '/'.$value['translit'];
                                                $checkUrl = true;
                                            }
                                        }
                                    }
                                }
                                $where[] = $orWhere;
                            }
                        }
                    }
                    if (!empty($this->region)) {
                        $addresSearchCheck = true;
                        if (is_array($this->region)) {
                            if (count($this->region) == 1) {
                                $regionInfo = Regions::findOne($this->region[0]);

                                if ($checkUrl) {
                                    $url .= '/'.$regionInfo->name_translit;
                                }
                                
                                $where[] = [
                                    'or',
                                    ['lot_regionid'=>$this->region[0]],
                                    ['like', 'LOWER(bnkr__address)', mb_strtolower($regionInfo->name, 'UTF-8')]
                                ];
                            } else {
                                $orWhere = ['or'];
                                foreach ($this->region as $key => $value) {
                                    $regionInfo = Regions::findOne($value);

                                    if ($key == 0 && $checkUrl) {
                                        $url .= '/'.$regionInfo->name_translit;
                                    }

                                    $orWhere[] = ['lot_regionid'=>$value];
                                    $orWhere[] = ['like', 'LOWER(bnkr__address)', mb_strtolower($regionInfo->name, 'UTF-8')];
                                }
                                $where[] = $orWhere;
                            }
                        } else {
                            $regionInfo = Regions::findOne($this->region);

                            if ($checkUrl) {
                                $url .= '/'.$regionInfo->name_translit;
                            }

                            $where[] = [
                                'or',
                                ['lot_regionid'=>$this->region[0]],
                                ['like', 'LOWER(bnkr__address)', mb_strtolower($regionInfo->name, 'UTF-8')]
                            ];
                        }
                    }
                    if (!empty($this->search)) {
                        $whereSearch = [
                            'or',
                            ['like', 'LOWER(lot_description)', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER(bnkr__name)', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER(bnkr__inn)', mb_strtolower($this->search, 'UTF-8')],
                        ];
                        if (!$addresSearchCheck) {
                            $whereSearch[] = ['like', 'LOWER(bnkr__address)', mb_strtolower($this->search, 'UTF-8')];
                        }
                        $where[] = $whereSearch;
                    }
                    if (!empty($this->etp)) {
                        if (count($this->etp) == 1) {
                            $where[] = ['lot_idtradeplace'=>$this->etp[0]];
                        } else {
                            $orWhere = ['or'];
                            foreach ($this->etp as $value) {
                                $orWhere[] = ['lot_idtradeplace'=>$value];
                            }
                            $where[] = $orWhere;
                        }
                    }
                    if (!empty($this->tradeType)) {
                        if (!empty($this->tradeType[0]) && empty($this->tradeType[1])) {
                            $where[] = ['torgy_tradetype'=>$this->tradeType[0]];
                        }
                        if (!empty($this->tradeType[1]) && empty($this->tradeType[0])) {
                            $where[] = ['torgy_tradetype'=>$this->tradeType[1]];
                        } else if (!empty($this->tradeType[1]) && !empty($this->tradeType[0])) {
                            $where = [
                                'or',
                                ['torgy_tradetype'=>$this->tradeType[0]],
                                ['torgy_tradetype'=>$this->tradeType[1]]
                            ];
                        }
                    }
                    $lotsPrice = Clone $lots;
                    if (!empty($this->minPrice)) {
                        $whereAnd[] = ['>=', 'lot_startprice', $this->minPrice];
                    }
                    if (!empty($this->maxPrice)) {
                        $whereAnd[] = ['<=', 'lot_startprice', $this->maxPrice];
                    }
                    // if (!empty($this->archivCheck)) {
                    //     if (!$this->archivCheck) {
                    //         $where[] = 'lot_timeend >= NOW()';
                    //     }
                    // } else {
                    //     $where[] = 'lot_timeend >= NOW()';
                    // }
                    if (!empty($this->imageCheck)) {
                        $where[] = ['lot_image' => $this->imageCheck];
                    }
                break;
            case 'arrest':
                    $where = ['and'];
                    $whereAnd = ['and'];
                    $addresSearchCheck = false;

                    $where[] = ['not', ['torgs."trgPublished"'=>null]];
                    
                    if ($this->category == '0') {
                        $url = $this->type.'/lot-list';
                    } else if (!empty($this->category) && $this->category != 'all') {
                        $category = LotsCategory::findOne($this->category);
                        $url = $this->type.'/'.$category->translit_name;

                        
                        if (empty($this->subCategory) || $this->subCategory == 'all' || $this->subCategory == '0') {
                            $orWhere = ['or'];
                            foreach ($category->arrest_categorys as $key => $value) {
                                $orWhere[] = ['lots.lotPropertyTypeId'=>$key];
                            }
                            $where[] = $orWhere;
                        } else if ($this->subCategory != '0'){
                            if (count($this->subCategory) == 1) {
                                foreach ($category->arrest_categorys as $key => $value) {
                                    if ($this->subCategory[0] == $key) {
                                        $where[] = ['lots.lotPropertyTypeId'=>$key];
                                        $url .= '/'.$value['translit'];
                                        $checkUrl = true;
                                    }
                                }
                            } else {
                                $orWhere = ['or'];
                                foreach ($this->subCategory as $keySubCategory => $subCategory) {
                                    foreach ($category->arrest_categorys as $key => $value) {
                                        if ($subCategory == $key) {
                                            $orWhere[] = ['lots.lotPropertyTypeId'=>$key];
                                            if ($keySubCategory == 0) {
                                                $url .= '/'.$value['translit'];
                                                $checkUrl = true;
                                            }
                                        }
                                    }
                                }
                                $where[] = $orWhere;
                            }
                        }
                    }
                    if (!empty($this->region)) {
                        $addresSearchCheck = true;
                        if (is_array($this->region)) {
                            if (count($this->region) == 1) {
                                $regionInfo = Regions::findOne($this->region[0]);

                                if ($checkUrl) {
                                    $url .= '/'.$regionInfo->name_translit;
                                }

                                $where[] = ['like', 'LOWER("lots"."lotKladrLocationName")', mb_strtolower($regionInfo->name, 'UTF-8')];
                            } else {
                                $orWhere = ['or'];
                                foreach ($this->region as $key => $value) {
                                    $regionInfo = Regions::findOne($value);

                                    if ($key == 0 && $checkUrl) {
                                        $url .= '/'.$regionInfo->name_translit;
                                    }
                                    $orWhere[] = ['like', 'LOWER("lots"."lotKladrLocationName")', mb_strtolower($regionInfo->name, 'UTF-8')];
                                }
                                $where[] = $orWhere;
                            }
                        } else {
                            $regionInfo = Regions::findOne($this->region);

                            if ($checkUrl) {
                                $url .= '/'.$regionInfo->name_translit;
                            }

                            $where[] = ['like', 'LOWER("lots"."lotKladrLocationName")', mb_strtolower($regionInfo->name, 'UTF-8')];
                        }
                    }
                    if (!empty($this->search)) {
                        $whereSearch = [
                            'or',
                            ['like', 'LOWER("lots"."lotTorgReason")', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER("lots"."lotPropName")', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER("lots"."lotKladrLocationName")', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER("lots"."lotBurdenDesc")', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER("lots"."lotDepositDesc")', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER("lots"."lotContractDesc")', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER("lots"."lotContractTerm")', mb_strtolower($this->search, 'UTF-8')]
                        ];
                        if (!$addresSearchCheck) {
                            $whereSearch[] = ['like', 'LOWER("lots"."lotKladrLocationName")', mb_strtolower($this->search, 'UTF-8')];
                        }
                        $where[] = $whereSearch;
                    }
                    if (!empty($this->tradeType)) {
                        if (!empty($this->tradeType[0]) && empty($this->tradeType[1])) {
                            $where[] = ['torgs.trgBidFormId'=>$this->tradeType[0]];
                        }
                        if (!empty($this->tradeType[1]) && empty($this->tradeType[0])) {
                            $where[] = ['torgs.trgBidFormId'=>$this->tradeType[1]];
                        } else if (!empty($this->tradeType[1]) && !empty($this->tradeType[0])) {
                            $where = [
                                'or',
                                ['torgs.trgBidFormId'=>$this->tradeType[0]],
                                ['torgs.trgBidFormId'=>$this->tradeType[1]]
                            ];
                        }
                    }
                    $lotsPrice = Clone $lots;
                    if (!empty($this->minPrice)) {
                        $whereAnd[] = ['>=', 'lots.lotStartPrice', $this->minPrice];
                    }
                    if (!empty($this->maxPrice)) {
                        $whereAnd[] = ['<=', 'lots.lotStartPrice', $this->maxPrice];
                    }
                    if (!empty($this->imageCheck)) {
                        $where[] = ['lot_image' => $this->imageCheck];
                    }
                    if (!empty($this->archivCheck)) {
                        if (!$this->archivCheck) {
                            $where[] = 'torgs."trgExpireDate" >= NOW()';
                        }
                    } else {
                        $where[] = 'torgs."trgExpireDate" >= NOW()';
                    }
                break;
                case 'zalog':
                    $where = ['and'];
                    $whereAnd = ['and'];
                    $addresSearchCheck = false;

                    $where[] = ['status'=>true];


                    if ($this->category == '0') {
                        $url = $this->type.'/lot-list';
                    } else if (!empty($this->category) && $this->category != 'all') {
                        $category = LotsCategory::findOne($this->category);
                        $url = $this->type.'/'.$category->translit_name;

                        
                        if (empty($this->subCategory) || $this->subCategory == 'all' || $this->subCategory == '0') {
                            $orWhere = ['or'];
                            foreach ($category->zalog_categorys as $key => $value) {
                                $orWhere[] = ['categorys.categoryId'=>$key];
                            }
                            $where[] = $orWhere;
                        } else if ($this->subCategory != '0'){
                            if (count($this->subCategory) == 1) {
                                foreach ($category->zalog_categorys as $key => $value) {
                                    if ($this->subCategory[0] == $key) {
                                        $where[] = ['categorys.categoryId'=>$key];
                                        $url .= '/'.$value['translit'];
                                        $checkUrl = true;
                                    }
                                }
                            } else {
                                $orWhere = ['or'];
                                foreach ($this->subCategory as $keySubCategory => $subCategory) {
                                    foreach ($category->zalog_categorys as $key => $value) {
                                        if ($subCategory == $key) {
                                            $orWhere[] = ['categorys.categoryId'=>$key];
                                            if ($keySubCategory == 0) {
                                                $url .= '/'.$value['translit'];
                                                $checkUrl = true;
                                            }
                                        }
                                    }
                                }
                                $where[] = $orWhere;
                            }
                        }
                    }
                    if (!empty($this->region)) {
                        $addresSearchCheck = true;
                        if (is_array($this->region)) {
                            if (count($this->region) == 1) {
                                $regionInfo = Regions::findOne($this->region[0]);

                                if ($checkUrl) {
                                    $url .= '/'.$regionInfo->name_translit;
                                }

                                $where[] = ['like', 'LOWER("address")', mb_strtolower($regionInfo->name, 'UTF-8')];
                                $where[] = ['like', 'LOWER("city")', mb_strtolower($regionInfo->name, 'UTF-8')];
                                $where[] = ['like', 'LOWER("country")', mb_strtolower($regionInfo->name, 'UTF-8')];
                            } else {
                                $orWhere = ['or'];
                                foreach ($this->region as $key => $value) {
                                    $regionInfo = Regions::findOne($value);

                                    if ($key == 0 && $checkUrl) {
                                        $url .= '/'.$regionInfo->name_translit;
                                    }
                                    $orWhere[] = ['like', 'LOWER("address")', mb_strtolower($regionInfo->name, 'UTF-8')];
                                    $orWhere[] = ['like', 'LOWER("city")', mb_strtolower($regionInfo->name, 'UTF-8')];
                                    $orWhere[] = ['like', 'LOWER("country")', mb_strtolower($regionInfo->name, 'UTF-8')];
                                }
                                $where[] = $orWhere;
                            }
                        } else {
                            $regionInfo = Regions::findOne($this->region);

                            if ($checkUrl) {
                                $url .= '/'.$regionInfo->name_translit;
                            }
                            
                            $where[] = ['like', 'LOWER("address")', mb_strtolower($regionInfo->name, 'UTF-8')];
                            $where[] = ['like', 'LOWER("city")', mb_strtolower($regionInfo->name, 'UTF-8')];
                            $where[] = ['like', 'LOWER("country")', mb_strtolower($regionInfo->name, 'UTF-8')];
                        }
                    }
                    if (!empty($this->search)) {
                        $whereSearch = [
                            'or',
                            ['like', 'LOWER("description")', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER("title")', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER("address")', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER("city")', mb_strtolower($this->search, 'UTF-8')],
                        ];
                        $where[] = $whereSearch;
                    }
                    if (!empty($this->etp)) {
                        if (count($this->etp) == 1) {
                            $where[] = ['ownerId'=>$this->etp[0]];
                        } else {
                            $orWhere = ['or'];
                            foreach ($this->etp as $value) {
                                $orWhere[] = ['ownerId'=>$value];
                            }
                            $where[] = $orWhere;
                        }
                    }
                    if (!empty($this->tradeType)) {
                        if (!empty($this->tradeType[0]) && empty($this->tradeType[1]) && $this->tradeType[0] == 'OpenedAuction') {
                            $where[] = ['tradeTipeId'=>(int)0];
                        }
                        if (!empty($this->tradeType[0]) && empty($this->tradeType[1]) && $this->tradeType[0] == 'PublicOffer') {
                            $where[] = ['tradeTipeId'=>(int)1];
                        } else if (!empty($this->tradeType[1]) && !empty($this->tradeType[0])) {
                            $where[] = [
                                'or',
                                ['tradeTipeId'=>(int)0],
                                ['tradeTipeId'=>(int)1]
                            ];
                        }
                    }
                    $lotsPrice = Clone $lots;
                    if (!empty($this->minPrice)) {
                        $whereAnd[] = ['>=', 'startingPrice', $this->minPrice];
                    }
                    if (!empty($this->maxPrice)) {
                        $whereAnd[] = ['<=', 'startingPrice', $this->maxPrice];
                    }
                    if (!empty($this->imageCheck)) {
                        $where[] = ['not', ['images' => NULL]];
                    }
                    if (!empty($this->archivCheck)) {
                        if (!$this->archivCheck) {
                            $where[] = '"endingDate" >= NOW()';
                        }
                    } else {
                        $where[] = '"endingDate" >= NOW()';
                    }
                break;
            default:
                return ['error' => 'Что то пошло не так :('];
                break;
        }

        return ['lots'=>$lots->where($where)->andWhere($whereAnd), 'lotsPrice'=>$lotsPrice->where($where), 'url'=>$url];
    }
}