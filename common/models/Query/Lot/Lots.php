<?php
namespace common\models\Query\Lot;

use yii\db\ActiveRecord;

use Yii;
use common\models\Query\LotsCategory;
use common\models\Query\WishList;
use common\models\Query\PageViews;

use common\models\Query\Lot\LotCategorys;
use common\models\Query\Lot\Documents;
use common\models\Query\Lot\LotPriceHistorys;
use common\models\Query\Lot\Participants;
use common\models\Query\Lot\Banks;
use common\models\Query\Lot\Torgs;

class Lots extends ActiveRecord
{
    public $whishCount;
    public $rank;

    public static function tableName()
    {
        return '"eiLot".{{lots}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    // Функции для вывода доп, инвормации
    public function getUrl()
    {

        $items = LotsCategory::find()->orderBy('id DESC')->all();
        foreach ($items as $value) {
            switch ($this->torg->type) {
                case 'bankrupt':
                        foreach ($this->categorys as $category) {
                            if ($value->bankrupt_categorys[$category->categoryId]['translit'] !== null) {
                                return 'bankrupt/'.$value->translit_name.'/'.$value->bankrupt_categorys[$category->categoryId]['translit'].'/'.$this->id;
                            }
                        }
                    break;
                case 'arrest':
                        foreach ($this->categorys as $category) {
                            if ($value->arrest_categorys[$category->categoryId]['translit'] !== null) {
                                return 'arrest/'.$value->translit_name.'/'.$value->arrest_categorys[$category->categoryId]['translit'].'/'.$this->id;
                            }
                        }
                    break;
                case 'zalog':
                        foreach ($this->categorys as $category) {
                            if ($value->zalog_categorys[$category->categoryId]['translit'] !== null) {
                                return 'zalog/'.$value->translit_name.'/'.$value->zalog_categorys[$category->categoryId]['translit'].'/'.$this->id;
                            }
                        }
                    break;
            }
        }
        switch ($this->torg->type) {
            case 'bankrupt':
                    return $this->torg->type.'/'.$items[0]->translit_name.'/'.$items[0]->bankrupt_categorys[1063]['translit'].'/'.$this->id;
                break;
            case 'arrest':
                    return $this->torg->type.'/'.$items[0]->translit_name.'/'.$items[0]->arrest_categorys[4]['translit'].'/'.$this->id;
                break;
            case 'zalog':
                    return $this->torg->type.'/'.$items[0]->translit_name.'/'.$items[0]->zalog_categorys[1101]['translit'].'/'.$this->id;
                break;
        }
    }
    public function getPrice() 
    {
        if ($this->torg->tradeTypeId == 2) {
            return $this->startPrice;
        } else {
            if ($this->thisPriceHistorys != null) {
                return $this->thisPriceHistorys->price;
            } else {
                return $this->startPrice;
            }
        }
    }
    public function getOldPrice() 
    {
        if ($this->torg->tradeTypeId == 2) {
            return false;
        }if ($this->priceHistorys != null) {
            $date = Yii::$app->formatter->asDatetime(new \DateTime(), "php:Y-m-d H:i:s");
            foreach ($this->priceHistorys as $key => $value) {
                if ($value->intervalBegin <= $date && $value->intervalEnd >= $date) {
                    if ($value->price == $this->startPrice) {
                        return false;
                    } else {
                        return ($this->priceHistorys[$key-1]->price)? $this->priceHistorys[$key-1]->price : $this->startPrice;
                    }
                }
            }
        } else {
            return false;
        }
    }
    public function getArchive()
    {
        $today = new \DateTime();

        if ($this->torg->endDate === null || $this->torg->completeDate === null ) {
            return false;
        } else {
            if (strtotime($this->torg->completeDate) <= strtotime($today->format('Y-m-d H:i:s')) || strtotime($this->torg->endDate) <= strtotime($today->format('Y-m-d H:i:s'))) {
                return true;
            }
        }
        return false;
    }
    public function getViewsCount() 
    {
        return count($this->views);
    }
    public function getWishId($id = null) 
    {
        if ($id != null) {
            if ($this->wishlist[0]) {
                foreach ($this->wishlist as $wish) {
                    if ($id == $wish->userId) {
                        return $wish->id;
                    }
                }
            }
        }
        return false;
    }

    // Поиск, главные значения
    public static function find()
    {
        return parent::find();
    }
    public static function isArchive()
    {
        return parent::find()->onCondition([
            'and', 
            [ 'published' => true ], 
            [ 
                'not',
                ['torg.publishedDate' => null],
            ],
        ]);
    }
    public static function isActive()
    {
        return parent::find()->onCondition([
            'and',
            [ 'published' => true ], 
            [ 
                'not',
                ['torg.publishedDate' => null],
            ],
            [
                'or',
                ['>=', 'torg.endDate', 'NOW()'],
                ['torg.endDate' => null],
            ],
            [
                'or',
                ['>=', 'torg.completeDate', 'NOW()'],
                ['torg.completeDate' => null],
            ]
        ]);
    }

    // Связь с таблицей статистики
    public function getWishlist()
    {
        return $this->hasMany(WishList::className(), ['lotId' => 'id'])->alias('wishList');
    }
    public function getViews()
    {
        return $this->hasMany(PageViews::className(), ['page_id' => 'oldId'])->alias('views')->onCondition([
            'page_type' => 'lot_'.$this->torg->type
        ]);
    }

    // Связи с таблицами
    public function getCategory()
    {
        return $this->hasOne(LotCategorys::className(), ['lotId' => 'id'])->alias('category'); // Категории лота
    }
    public function getCategorys()
    {
        return $this->hasMany(LotCategorys::className(), ['lotId' => 'id'])->alias('categorys'); // Категории лота
    }
    public function getDocuments()
    {
        return $this->hasMany(Documents::className(), ['tableId' => 'id'])->alias('documents')->onCondition(['documents.tableTypeId'=>1]); // Файлы лота
    }
    public function getPriceHistorys()
    {
        return $this->hasMany(LotPriceHistorys::className(), ['lotId' => 'id'])->alias('priceHistorys'); // История снижения цен лота
    }
    public function getThisPriceHistorys()
    {
        return $this->hasOne(LotPriceHistorys::className(), ['lotId' => 'id'])->alias('thisPriceHistorys')->onCondition([
                'and',
                ['<=', 'thisPriceHistorys.intervalBegin', 'NOW()'],
                ['>=', 'thisPriceHistorys.intervalEnd', 'NOW()']
            ]); // действующая История снижения цен лота
    }
    public function getParticipants()
    {
        return $this->hasMany(Participants::className(), ['lotId' => 'id'])->alias('participants'); // Участники лота
    }
    public function getBank()
    {
        return $this->hasOne(Banks::className(), ['id' => 'bankId'])->alias('bank'); // Банк
    }
    public function getTorg()
    {
        return $this->hasOne(Torgs::className(), ['id' => 'torgId'])->alias('torg'); // Торги лота
    }
}