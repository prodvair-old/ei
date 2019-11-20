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

class Lots extends ActiveRecord 
{
    public static function tableName()
    {
        return 'uds.{{%lots}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function getLotTitle() 
    {
        foreach ($this->purchaselots as $key => $value) {
            if ($value->pheLotNumber == $this->lotid) {
                $lotName  = $value->pheLotName;
            }
        }
        $shortTitle = (strlen($this->description) < 80)? $this->description : mb_substr($this->description, 0, 70, 'UTF-8').'...';
        return ($lotName != null)? $lotName : $shortTitle;
    }
    public function getLotStatus() 
    {
        $value = Value::param($this->torgy->state);
        return $value['value'];
    }
    public function getLot_timepublication() 
    {
        return $this->torgy->timepublication;
    }
    public function getLotDateEnd() 
    {
        return $this->torgy->timeend;
    }
    public function getLotDateStart() 
    {
        return $this->torgy->timebegin;
    }
    public function getLotTradeType() 
    {
        return $this->torgy->tradetype;
    }
    public function getLotPrice() 
    {
        if ($this->torgy->tradetype == 'OpenedAuction') {
            return $this->startprice;
        } else {
            if ($this->offer != null) {
                $date = Yii::$app->formatter->asDatetime(new \DateTime(), "php:Y-m-d H:i:s");
                foreach ($this->offer as $key => $value) {
                    if ($value->ofrRdnLotNumber == $this->lotid && ($key == 0 || $value->ofrRdnDateTimeBeginInterval <= $date) && $value->ofrRdnDateTimeEndInterval >= $date) {
                        return $value->ofrRdnPriceInInterval;
                    }    
                }
            } else {
                return $this->startprice;
            }
        }
    }
    public function getLotOldPrice() 
    {
        if ($this->torgy->tradetype == 'OpenedAuction') {
            return false;
        }if ($this->offer != null) {
            $date = Yii::$app->formatter->asDatetime(new \DateTime(), "php:Y-m-d H:i:s");
            foreach ($this->offer as $key => $value) {
                if ($value->ofrRdnLotNumber == $this->lotid && ($key == 0 || $value->ofrRdnDateTimeBeginInterval <= $date) && $value->ofrRdnDateTimeEndInterval >= $date) {
                    if ($value->ofrRdnPriceInInterval == $this->startprice) {
                        return false;
                    } else {
                        return ($this->offer[$key-1]->ofrRdnPriceInInterval)? $this->offer[$key-1]->ofrRdnPriceInInterval : $this->startprice;
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
    public function getLotEtpId() 
    {
        return $this->torgy->idtradeplace;
    }
    public function getLotSro() 
    {
        return $this->torgy->case->arbitr->sro;
    }
    public function getLotSroTitle() 
    {
        return $this->torgy->case->arbitr->sro->title;
    }
    public function getLotPeriod() 
    {
        return $this->torgy->timebegin.' - '.$this->torgy->timeend;
    }
    public function getLotEtp() 
    {
        return $this->torgy->tradesite;
    }
    public function getLotMsgId() 
    {
        return $this->torgy->msgid;
    }
    public function getLot_archive()
    {
        $today = new \DateTime();
        if (strtotime($this->torgy->timeend) <= strtotime($today->format('Y-m-d H:i:s'))) {
            return true;
        } else {
            return false;
        }
    }
    public function getLotUrl()
    {
        $items = [
            'transport_i_tekhnika' => '1060,1062,1073,1075,1077,1092,1145,1176,1124,1120,1118,1127',
            'nedvizhimost' => '1061,1064,1068,1078,1088,1090,1136,1148,1157,1161,1140,1143,1102,1173',
            'oborudovanie' => '1065,1066,1071,1083,1093,1105,1114,1115,1116,1130,1201,1096,1101,1106,1111,1113,1117,1123,1135,1147,1141,1094,1095,1100,1126,1192',
            'selskoe_hozyajstvo' => '1079,1179,1172,1185,1188,1189,1110,1191,1137,1200',
            'imushchestvennyj_kompleks' => '1067,1069,1070,1076,1089,1098,1099,1108,1112,1119,1129,1131,1132,1134,1138,1149,1151,1190',
            'tovarno-materialnye_cennosti' => '1080,1081,1082,1084,1152,1154,1159,1160,1168,1175,1177,1180,1181,1183',
            'debitorskaya_zadolzhennost' => '1121,1142,1169,1170,1199',
            'cennye_bumagi_nma_doli_v_ustavnyh_kapitalah' => '1072,1074,1091,1144,1166,1171',
            'syre' => '1085,1086,1087,1103,1104,1128,1133,1139,1153,1158,1165,1174,1187,1186',
            'prochee' => '1063,1097,1107,1109,1122,1125,1150,1155,1156,1162,1163,1164,1167,1178,1182,1184,1193'
        ];
        foreach ($items as $key => $value) {
            foreach ($this->category as $category) {
                $classes = explode(',',$value);
                foreach ($classes as $class) {
                    if ($class == $category->lotclassifier) {
                        return 'bankrupt/'.$key.'/'.Translit::widget(['text' => (Value::param($class))['value']]).'/'.$this->id;
                    }
                }
            }
        }
    }
    public function getLotCategoryTranslit()
    {
        $items = [
            'transport_i_tekhnika' => '1060,1062,1073,1075,1077,1092,1145,1176,1124,1120,1118,1127',
            'nedvizhimost' => '1061,1064,1068,1078,1088,1090,1136,1148,1157,1161,1140,1143,1102,1173',
            'oborudovanie' => '1065,1066,1071,1083,1093,1105,1114,1115,1116,1130,1201,1096,1101,1106,1111,1113,1117,1123,1135,1147,1141,1094,1095,1100,1126,1192',
            'selskoe_hozyajstvo' => '1079,1179,1172,1185,1188,1189,1110,1191,1137,1200',
            'imushchestvennyj_kompleks' => '1067,1069,1070,1076,1089,1098,1099,1108,1112,1119,1129,1131,1132,1134,1138,1149,1151,1190',
            'tovarno-materialnye_cennosti' => '1080,1081,1082,1084,1152,1154,1159,1160,1168,1175,1177,1180,1181,1183',
            'debitorskaya_zadolzhennost' => '1121,1142,1169,1170,1199',
            'cennye_bumagi_nma_doli_v_ustavnyh_kapitalah' => '1072,1074,1091,1144,1166,1171',
            'syre' => '1085,1086,1087,1103,1104,1128,1133,1139,1153,1158,1165,1174,1187,1186',
            'prochee' => '1063,1097,1107,1109,1122,1125,1150,1155,1156,1162,1163,1164,1167,1178,1182,1184,1193'
        ];
        foreach ($items as $key => $value) {
            foreach ($this->category as $category) {
                $classes = explode(',',$value);
                foreach ($classes as $class) {
                    if ($class == $category->lotclassifier) {
                        return $key.'/'.Translit::widget(['test' => (Value::param($class))['value']]);
                    }
                }
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
        $images = Images::find()->where(['objid' => $this->id])->all();
        try {
            foreach ($images as $image) {
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
            $result[] = 'https://ei.ru/img/lot/'.$image->objid.'/'.$image->fileurl;
        }
        
        return $result;
    }
    public function getLotImageFromId() 
    {
        try {
            $images = Images::find()->where(['objid' => $this->id])->all();
            foreach ($images as $image) {
                if (!$imageUrl = Yii::$app->cache->get('lot_image-'.$image->objid.'-name-'.$image->fileurl)) {
                    $imageUrl = '/img/lot/'.$image->objid.'/min-'.$image->fileurl;
                    Image::frame(Yii::getAlias('@frontendWeb/img/lot/'.$image->objid.'/'.$image->fileurl))
                        ->thumbnail(new Box(250, 250))
                        ->save(Yii::getAlias('@frontendWeb'.$imageUrl), ['quality' => 50]);

                    Yii::$app->cache->set('lot_image-'.$image->objid.'-name-'.$image->fileurl, $imageUrl, 3600*24*7);
                }
                
                $result[] = [
                    'id' => $image->id,
                    'url' => $imageUrl,
                    'fileName' => $image->fileurl,
                ];
                
            }
        } catch (\Throwable $th) {
            $result[] = [
                'id' => $image->id,
                'url' => 'https://ei.ru/img/lot/'.$image->objid.'/'.$image->fileurl,
                'fileName' => $image->fileurl,
            ];
            ;
        }
        
        return $result;
    }
    public function getLotBnkrId() 
    {
        return $this->torgy->case->bnkr->id;
    }
    public function getLotType()
    {
        return 'lots';
    }
    public function getLotBnkrType() 
    {
        return $this->torgy->case->bnkr->bankrupttype;
    }
    public function getLotBnkrName() 
    {
        if ($this->torgy->case->bnkr->bankrupttype == 'Person') {
            return $this->torgy->case->bnkr->person->lname.' '.$this->torgy->case->bnkr->person->fname.' '.$this->torgy->case->bnkr->person->mname;
        } else {
            return $this->torgy->case->bnkr->company->fullname;
        }
    }
    public function getLotArbtrName() 
    {
        return $this->torgy->case->arbitr->person->lname.' '.$this->torgy->case->arbitr->person->fname.' '.$this->torgy->case->arbitr->person->mname;
    }
    public function getLotArbtrId() 
    {
        try {
            return $this->torgy->case->arbitr->id;
        } catch (\Throwable $th) {
            $result[] = null;
        }
    }
    public function getLotWishId() 
    {
        try {
            return $this->wishlist->id;
        } catch (\Throwable $th) {
            $result[] = null;
        }
    }
    public function getLotAddress() 
    {
        if ($this->cadastreid != null) {
            try {
                return ucwords($this->cadastre->title);
            } catch (\Throwable $th) {
                $result[] = null;
            }
        } 
        if ($this->torgy->case->bnkr->bankrupttype == 'Person') {
            return ucwords($this->torgy->case->bnkr->person->address, 'UTF-8');
        } else {
            return ucwords(mb_strtolower($this->torgy->case->bnkr->company->postaddress, 'UTF-8'));
        }
    }
    public function getLotCadastre() 
    {
        $kadastr_check = preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{6,7}:[0-9]{1,35}/", $this->description, $kadastr);
        return ($kadastr_check)? $kadastr[0] : false;
    }
    public function getLotVin() 
    {
        $vin_text = str_replace('VIN', '',$this->description);
        $vin_check = preg_match("/[ABCDEFGHJKLMNPRSTUVWXYZ,0-9]{17}/", $vin_text, $vin_t);
        $vin_c = preg_match("/[\w\s\d]+/u", $vin_t[0], $vin);
        return ($vin_check)? $vin[0] : false;
    }
    // Атрибуты для поиска
    public function attributeLabels() 
    {
        return [
            'id' => 'ID Лота',
            'lotTitle'          => 'lot_title',
            'lotStatus'         => 'lot_status',
            'lotPublication'    => 'lot_publication',
            'lotDateEnd'        => 'lot_date_end',
            'lotDateStart'      => 'lot_date_start',
            'lotAuctionId'      => 'lot_auction_id',
            'lotTradeType'      => 'lot_trade_type',
            'lotPrice'          => 'Стоимость',
            'lotOldPrice'       => 'lot_old_price',
            'lotEtpId'          => 'lot_etp_id',
            'lotCategory'       => 'lot_category',
            'lotImage'          => 'lot_images',
            'lotBnkrId'         => 'lot_bnkr_id',
            'lotArbtrId'        => 'lot_arbtr_id',
            'lotArbtrName'      => 'Кем опубликован',
            'lotAddress'        => 'lot_address',
            'lotCategoryTranslit' => 'lot_category_translit',
            'lotCadastre'       => 'lot_cadastre_id',
            'lotWishId'         => 'wish_id',
            'lotBnkrType'       => 'lot_bnkr_type',
            'lotBnkrName'       => 'Должник',
            'lotSroTitle'       => 'СРО',
            'lotPeriod'         => 'Период',
            'lotEtp'            => 'ЭТП',
            'lotMsgId'          => '№ сообщения',
        ];
    }
    // Все поля
    public function fields()
    {
        return [
            'lot_id'            => 'id',
            'lot_auction_id'    => 'auctionid',
            'lot_cadastre_id'   => 'cadastreid',
            'lot_cadastre'      => 'cadastre',
            'lot_vin'           => 'vin',
            'lot_description'   => 'description',
            'lot_start_price'   => 'startprice',
            'lot_step_price'    => 'stepprice',
            'lot_advance'       => 'advance',
            'lot_auction_step_unit' => 'auctionstepunit',
            'lot_pricere_duction'   => 'pricereduction',
            // Другие поля для поиска
            'lot_title'         => 'lotTitle',
            'lot_status'        => 'lotStatus',
            'lot_publication'   => 'lotPublication',
            'lot_date_end'      => 'lotDateEnd',
            'lot_date_start'    => 'lotDateStart',
            'lot_trade_type'    => 'lotTradeType',
            'lot_price'         => 'lotPrice',
            'lot_old_price'     => 'lotOldPrice',
            'lot_etp_id'        => 'lotEtpId',
            'lot_images'        => 'lotImage',
            'lot_bnkr_id'       => 'lotBnkrId',
            'lot_arbtr_id'      => 'lotArbtrId',
            'lot_address'       => 'lotAddress',
            'lot_category'      => 'lotCategory',
            'lot_cadastre_id'   => 'lotCadastre',
            'lot_category_translit' => 'lotCategoryTranslit',
            'wish_id'           => 'lotWishId',
            'lotType'           => 'lotType',
            'lot_views'         => 'lotViews'
        ];
    }

    // Связи с таблицами
    public function getViews()
    {
        return $this->hasMany(PageViews::className(), ['page_id' => 'id'])->alias('views')->onCondition([
            'page_type' => 'lot_bankrupt'
        ]);
    }
    public function getTorgy()
    {
        return $this->hasOne(Torgy::className(), ['id' => 'auctionid'])->alias('torgy');
    }
    public function getWishlist()
    {
        return $this->hasOne(WishList::className(), ['lotId' => 'id'])->alias('wish')->onCondition([
            'type' => 'bankrupt'
        ]);
    }
    public function getCategory()
    {
        return $this->hasMany(Categorys::className(), ['lotid' => 'id'])->alias('category');
    }
    public function getImages()
    {
        return $this->hasMany(Images::className(), ['objid' => 'id'])->alias('img');
    }
    public function getCadastrelist()
    {
        return $this->hasOne(Cadastre::className(), ['id' => 'cadastreid'])->alias('cadastrelist');
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
    public static function find()
    {
        return parent::find()->onCondition([
            'visible' => 'true'
        ]);
    }
}

// Таблица ЭТП
class Etp extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{tradeplace}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function fields()
    {
        return [
            'etp_id'        => 'idtradeplace',
            'etp_inn'       => 'inn',
            'etp_url'       => 'tradesite',
            'etp_name'      => 'tradename',
            'etp_fullname'  => 'ownername',
        ];
    }
}

// Таблица Кадастрового номера
class Cadastre extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%cadastre}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}