<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use Imagine\Image\Box;
use yii\imagine\Image;
use common\components\Translit;
use common\models\Query\Bankrupt\Cases;
use common\models\Query\Bankrupt\Categorys;
use common\models\Query\Bankrupt\Images;
use common\models\Query\Bankrupt\Links;
use common\models\Query\Bankrupt\Value;
use common\models\Query\Bankrupt\Bankrupts;
use common\models\Query\Bankrupt\Company;
use common\models\Query\Bankrupt\Persons;
use common\models\Query\Bankrupt\Torgy;
use common\models\Query\Bankrupt\Offerreductions;
use common\models\Query\Bankrupt\Purchaselots;

use common\models\Query\WishList;
use common\models\Query\PageViews;
use common\models\Query\LotsCategory;

use frontend\models\getUserId;

class LotsBankrupt extends ActiveRecord 
{
    public static function tableName()
    {
        return 'uds.{{lots_list}}';
    }
    public static function primaryKey()
    {
        return ['lot_id'];
    }
    public function getLotTitle() 
    {
        foreach ($this->purchaselots as $key => $value) {
            if ($value->pheLotNumber == $this->lot_lotid) {
                $lotName  = $value->pheLotName;
            }
        }
        $shortTitle = (strlen($this->lot_description) < 80)? $this->lot_description : mb_substr($this->lot_description, 0, 70, 'UTF-8').'...';
        return ($lotName != null)? $lotName : $shortTitle;
    }
    public function getLotPrice() 
    {
        if ($this->torgy_tradetype == 'OpenedAuction') {
            return $this->lot_startprice;
        } else {
            if ($this->offer != null) {
                $date = Yii::$app->formatter->asDatetime(new \DateTime(), "php:Y-m-d H:i:s");
                foreach ($this->offer as $key => $value) {
                    if ($value->ofrRdnLotNumber == $this->lot_lotid && ($key == 0 || $value->ofrRdnDateTimeBeginInterval <= $date) && $value->ofrRdnDateTimeEndInterval >= $date) {
                        return $value->ofrRdnPriceInInterval;
                    }    
                }
            } else {
                return $this->lot_startprice;
            }
        }
    }
    public function getLotId() 
    {
        return $this->lot_id;
    }
    public function getLotOldPrice() 
    {
        if ($this->torgy_tradetype == 'OpenedAuction') {
            return false;
        }if ($this->offer != null) {
            $date = Yii::$app->formatter->asDatetime(new \DateTime(), "php:Y-m-d H:i:s");
            foreach ($this->offer as $key => $value) {
                if ($value->ofrRdnLotNumber == $this->lot_lotid && ($key == 0 || $value->ofrRdnDateTimeBeginInterval <= $date) && $value->ofrRdnDateTimeEndInterval >= $date) {
                    if ($value->ofrRdnPriceInInterval == $this->lot_startprice) {
                        return false;
                    } else {
                        return ($this->offer[$key-1]->ofrRdnPriceInInterval)? $this->offer[$key-1]->ofrRdnPriceInInterval : $this->lot_startprice;
                    }
                }
            }
        } else {
            return false;
        }
    }
    public function getLotViews() 
    {
        return count($this->views);
    }
    public function getLotUrl()
    {
        $items = LotsCategory::find()->all();
        foreach ($items as $value) {
            if ($value->bankrupt_categorys[$this->category[0]->lotclassifier]['translit'] !== null) {
                return 'bankrupt/'.$value->translit_name.'/'.$value->bankrupt_categorys[$this->category[0]->lotclassifier]['translit'].'/'.$this->lot_id;
            }
        }
    }
    public function getLotCategory() 
    {
        foreach ($this->category as $category) {
            $value = Value::param($category->lotclassifier);
            $result[] = $value['value'];
        }
        return $result;
    }
    public function getLotCategorys() 
    {
        foreach ($this->category as $category) {
            $value = Value::param($category->lotclassifier);
            $result[$category->lotclassifier] = $value['value'];
        }
        return $result;
    }
    public function getLotImage() 
    {
        if ($this->lot_image) {
            try {
                foreach ($this->images as $image) {
                    if (!$imageUrl = Yii::$app->cache->get('lot_image-1'.$image->objid.'-name-'.$image->fileurl)) {
                        $imageUrl = '/img/lot/'.$image->objid.'/min-'.$image->fileurl;
                        $imagePath = Yii::getAlias('@frontendWeb/img/lot/'.$image->objid.'/'.$image->fileurl);
                        
                        Image::thumbnail($imagePath, 250, 250)
                            ->save(Yii::getAlias('@frontendWeb'.$imageUrl), ['quality' => 50]);
    
                        Yii::$app->cache->set('lot_image-'.$image->objid.'-name-'.$image->fileurl, $imageUrl, 3600*24*7);
                    }
                    
                    $result[] = $imageUrl;
                    
                }
            } catch (\Throwable $th) {
                foreach ($this->images as $image) {
                    $result[] = 'http://n.ei.ru/img/lot/'.$image->objid.'/'.$image->fileurl;
                }
            }
            
            return $result;
        } else {
            return false;
        }
        
    }
    public function getLotType()
    {
        return 'bankrupt';
    }
    public function getLotWishId() 
    {
        try {
            return $this->wishlist->id;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function getLotAddress() 
    {
        if ($this->lot_cadastreid != null) {
            try {
                return ucwords($this->cadastrelist->title);
            } catch (\Throwable $th) {
                return ucwords($this->bnkr__address);
            }
        } else {
            return ucwords($this->bnkr__address);
        }
    }
    // // Атрибуты для поиска
    // public function attributeLabels() 
    // {
    //     return [
    //         'id' => 'ID Лота',
    //         'lotTitle'          => 'lot_title',
    //         'lotStatus'         => 'lot_status',
    //         'lotPublication'    => 'lot_publication',
    //         'lotDateEnd'        => 'lot_date_end',
    //         'lotDateStart'      => 'lot_date_start',
    //         'lotAuctionId'      => 'lot_auction_id',
    //         'lotTradeType'      => 'lot_trade_type',
    //         'lotPrice'          => 'Стоимость',
    //         'lotOldPrice'       => 'lot_old_price',
    //         'lotEtpId'          => 'lot_etp_id',
    //         'lotCategory'       => 'lot_category',
    //         'lotImage'          => 'lot_images',
    //         'lotAddress'        => 'lot_address',
    //         'lotCategoryTranslit' => 'lot_category_translit',
    //         'lotCadastre'       => 'lot_cadastre_id',
    //         'lotWishId'         => 'wish_id',
    //         'lotBnkrType'       => 'lot_bnkr_type',
    //         'lotBnkrName'       => 'Должник',
    //         'lotSroTitle'       => 'СРО',
    //         'lotPeriod'         => 'Период',
    //         'lotEtp'            => 'ЭТП',
    //         'lotMsgId'          => '№ сообщения',
    //     ];
    // }
    // Все поля
    // public function fields()
    // {
    //     return [
    //         'lot_id'            => 'lot_id',
    //         'lot_torgy_id'      => 'torgy_id',
    //         'lot_description'   => 'lot_description',
    //         'lot_cadastre_id'   => 'lot_cadastreid',
    //         'lot_start_price'   => 'lot_startprice',
    //         'lot_step_price'    => 'lot_stepprice',
    //         'lot_advance'       => 'lot_advance',
    //         'lot_status'        => 'torgy_status',
    //         'lot_publication'   => 'lot_timepublication',
    //         'lot_tradetype'     => 'torgy_tradetype',
    //         'lot_pricetype'     => 'torgy_pricetype',
    //         'lot_date_start'    => 'lot_timebegin',
    //         'lot_date_end'      => 'lot_timeend',
    //         'lot_rules'         => 'torgy_rules',
    //         'lot_etp'           => 'lot_tradename',
    //         'lot_etp_link'      => 'lot_tradesite',
    //         'lot_etp_id'        => 'lot_idtradeplace',
    //         'lot_bnkt_inn'      => 'bnkr__inn',
    //         'lot_bnkt_name'     => 'bnkr__name',
    //         'lot_bnkt_addres'   => 'bnkr__address',
    //         'lot_case_id'       => 'case_id',
    //         'lot_regionid'      => 'lot_regionid',
    //         'lot_areaid'        => 'lot_areaid',
    //         'lot_image_check'   => 'lot_image',
    //         'lot_advance_step_unit' => 'lot_advancestepunit',
    //         'lot_auction_step_unit' => 'lot_auctionstepunit',
    //         'lot_pricere_duction'   => 'lot_pricereduction',
    //         'lot_torgy_description' => 'torgy_description',
    //         // Другие поля для поиска
    //         'lot_title'         => 'lotTitle',
    //         'lot_price'         => 'lotPrice',
    //         'lot_old_price'     => 'lotOldPrice',
    //         'lot_images'        => 'lotImage',
    //         'lot_address'       => 'lotAddress',
    //         'lot_category'      => 'lotCategory',
    //         'lot_cadastre'      => 'lotCadastre',
    //         'lot_vin'           => 'lotVin',
    //         // 'lot_category_translit' => 'lotCategoryTranslit',
    //         'wish_id'           => 'lotWishId',
    //         'lot_type'           => 'lotType',
    //         'lot_views'         => 'lotViews'
    //     ];
    // }

    // Связи с таблицами
    public function getViews()
    {
        return $this->hasMany(PageViews::className(), ['page_id' => 'lot_id'])->alias('views')->onCondition([
            'page_type' => 'lot_bankrupt'
        ]);
    }
    public function getTorgy()
    {
        return $this->hasOne(Torgy::className(), ['id' => 'torgy_id'])->alias('torgy');
    }
    public function getWishlist()
    {
        return $this->hasOne(WishList::className(), ['lotId' => 'lot_id'])->alias('wish')->onCondition([
            'type' => 'bankrupt',
            'userId' => getUserId::getId()
        ]);
    }
    public function getCategory()
    {
        return $this->hasMany(Categorys::className(), ['lotid' => 'lot_id'])->alias('category');
    }
    public function getImages()
    {
        return $this->hasMany(Images::className(), ['objid' => 'lot_id'])->alias('img');
    }
    public function getCadastrelist()
    {
        return $this->hasOne(Cadastre::className(), ['lot_id' => 'cadastreid'])->alias('cadastrelist');
    }
    public function getOffernow()
    {
        $date = Yii::$app->formatter->asDatetime(new \DateTime(), "php:Y-m-d H:i:s");
        return $this->hasMany(Offerreductions::className(), ['ofrRdnNumberInEFRSB'=>'msgid'])->alias('offernow')->via('torgy')->onCondition([
            'and',
            ['<=', 'offernow.ofrRdnDateTimeBeginInterval', 'now()'],
            ['>=', 'offernow.ofrRdnDateTimeEndInterval', 'now()']
        ]);
    }
    public function getOffer()
    {
        return $this->hasMany(Offerreductions::className(), ['ofrRdnNumberInEFRSB'=>'msgid'])->alias('offer')->via('torgy');
    }
    public function getPurchaselots()
    {
        return $this->hasMany(Purchaselots::className(), ['pheLotNumberInEFRSB'=>'msgid'])->via('torgy');
    }
    // Поиск, главные значения
    // public static function find()
    // {
    //     return parent::find()->onCondition([
    //         'visible' => 'true'
    //     ]);
    // }
}

// // Таблица Кадастрового номера
// class Cadastre extends ActiveRecord
// {
//     public static function tableName()
//     {
//         return 'uds.{{%cadastre}}';
//     }
//     public static function getDb()
//     {
//         return Yii::$app->get('db');
//     }
// }