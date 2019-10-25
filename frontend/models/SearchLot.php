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
            [['imageCheck'], 'boolean'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function search($lots, $type = null, $category = null)
    {
        if (!$this->validate()) {
            return null;
        }

        if (!empty($type) && empty($this->type)) {
            $this->type = $type;
        }
        if (!empty($category) && $category != 'all' && empty($this->category)) {
            $this->category = $category;
        }
        
        switch ($this->type) {
            case 'bankrupt':
                    $where = ['and'];
                    $whereAnd = ['and'];
                    if (!empty($this->search)) {
                        $where[] = [
                            'or',
                            ['like', 'LOWER(lot_description)', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER(torgy_description)', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER(bnkr__name)', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER(bnkr__inn)', mb_strtolower($this->search, 'UTF-8')],
                            ['like', 'LOWER(bnkr__address)', mb_strtolower($this->search, 'UTF-8')],
                        ];
                    }
                    if (!empty($this->category) && $this->category != 'all') {
                        $category = LotsCategory::findOne($this->category);
                        $lots->joinWith('category');

                        if (empty($this->subCategory) || $this->subCategory == 'all') {
                            $orWhere = ['or'];
                            foreach ($category->bankrupt_categorys as $key => $value) {
                                $orWhere[] = ['category.lotclassifier'=>$key];
                            }
                            $where[] = $orWhere;
                        } else {
                            $subCategory = explode(';',$this->subCategory);

                            if (count($subCategory) == 1) {
                                $where[] = ['category.lotclassifier'=>$subCategory[0]];
                            } else {
                                $orWhere = ['or'];
                                foreach ($subCategory as $value) {
                                    $orWhere[] = ['category.lotclassifier'=>$value];
                                }
                                $where[] = $orWhere;
                            }

                        }
                    }
                    if (!empty($this->region)) {
                        if (count($this->region) == 1) {
                            $regionInfo = Regions::findOne($this->region[0]);

                            $where[] = [
                                'or',
                                ['lot_regionid'=>$this->region[0]],
                                ['like', 'LOWER(bnkr__address)', mb_strtolower($regionInfo->name, 'UTF-8')]
                            ];
                        } else {
                            $orWhere = ['or'];
                            foreach ($this->region as $value) {
                                $regionInfo = Regions::findOne($value);

                                $orWhere[] = ['lot_regionid'=>$value];
                                $orWhere[] = ['like', 'LOWER(bnkr__address)', mb_strtolower($regionInfo->name, 'UTF-8')];
                            }
                            $where[] = $orWhere;
                        }
                    }
                    if (!empty($this->etp)) {
                        $etp = explode(';',$this->etp);

                        if (count($etp) == 1) {
                            $where[] = ['lot_idtradeplace'=>$etp[0]];
                        } else {
                            $orWhere = ['or'];
                            foreach ($etp as $value) {
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
                    if (!empty($this->minPrice)) {
                        $whereAnd[] = ['>=', 'lot_startprice', $this->minPrice];
                    }
                    if (!empty($this->maxPrice)) {
                        $whereAnd[] = ['<=', 'lot_startprice', $this->maxPrice];
                    }
                    if (!empty($this->imageCheck)) {
                        $where[] = ['lot_image' => $this->imageCheck];
                    }
                break;
            case 'arrest':
                
                break;
            default:
                return ['error' => 'Что то пошло не так :('];
                break;
        }

        return ['lots'=>$lots->where($where)->andWhere($whereAnd), 'lotsPrice'=>$lots->where($where)];
    }
}
 