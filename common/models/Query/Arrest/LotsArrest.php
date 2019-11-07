<?php
namespace common\models\Query\Arrest;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

use common\models\Query\Arrest\Documents;
use common\models\Query\Arrest\LotDocuments;

use common\models\Query\WishList;
use common\models\Query\PageViews;
use common\models\Query\LotsCategory;

// Таблица лотов арестовки
class LotsArrest extends ActiveRecord
{
    public static function tableName()
    {
        return 'bailiff.{{lots}}';
    }
    public function getLotTitle() 
    {
        return (strlen($this->lotPropName) < 140)? $this->lotPropName : mb_substr($this->lotPropName, 0, 130, 'UTF-8').'...';
    }
    public function getLotUrl() {
        $items = LotsCategory::find()->all();
        foreach ($items as $value) {
            if ($value->arrest_categorys[$this->lotPropertyTypeId]['translit'] !== null) {
                return 'arrest/'.$value->translit_name.'/'.$value->arrest_categorys[$this->lotPropertyTypeId]['translit'].'/'.$this->lotId;
            }
        }
    }
    public function getLot_timepublication() 
    {
        return $this->torgs->trgPublished;
    }
    public function getLotImage()
    {
        return ($this->lotImages[0] !== null)? $this->lotImages : null;
    }
    public function getLotDateEnd() 
    {
        return $this->torgs->trgExpireDate;
    }
    public function getLotDateStart() 
    {
        return $this->torgs->trgStartDateRequest;
    }
    public function getLotPrice() 
    {
        return $this->lotStartPrice;
    }
    public function getLotOldPrice() 
    {
        return null;
    }
    public function getLotType()
    {
        return 'lots_arrest';
    }
    public function getLotCadastre() 
    {
        $kadastr_check = preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{6,7}:[0-9]{1,35}/", $this->lotPropName, $kadastr);
        return ($kadastr_check)? $kadastr[0] : false;
    }
    public function getLotVin() 
    {
        $vin_text = str_replace('VIN', '',$this->lotPropName);
        $vin_check = preg_match("/[ABCDEFGHJKLMNPRSTUVWXYZ,0-9]{17}/", $vin_text, $vin);
        return ($vin_check)? $vin[0] : false;
    }
    public function getLotWishId() 
    {
        try {
            return $this->wishlist->id;
        } catch (\Throwable $th) {
            $result[] = null;
        }
    }
    public function getLotViews() 
    {
        return count($this->views);
    }
    public function getLotCategory() {
        $categorys[0] = (explode(';',$this->lotPropertyTypeName))[1];
        return $categorys;
    }
    public function fields()
    {
        return [
            'lot_id'            => 'lotId',
            'lot_description'   => 'lotPropName',
            'lot_start_price'   => 'lotStartPrice',
            'lot_step_price'    => 'lotPriceStep',
            'lot_advance'       => 'lotDepositSize',
            'lot_address'       => 'lotKladrLocationName',
            // Другие поля для поиска
            'lot_title'         => 'lotTitle',
            'lot_images'        => 'lotImage',
            'lot_publication'   => 'lotPublication',
            'lot_date_end'      => 'lotDateEnd',
            'lot_date_start'    => 'lotDateStart',
            'lot_price'         => 'lotPrice',
            'lot_category'      => 'lotPropertyTypeName',
            'lot_cadastre'      => 'lotCadastre',
            'lot_vin'           => 'lotVin',
            'lot_category_translit' => 'lotCategoryTranslit',
            'wish_id'           => 'lotWishId',
            'lotType'           => 'lotType'

        ];
    }
    public function getViews()
    {
        return $this->hasMany(PageViews::className(), ['page_id' => 'lotId'])->alias('views')->onCondition(['page_type' => 'lot_arrest']);
    }
    public function getLotDocuments()
    {
        return $this->hasMany(LotDocuments::className(), ['ldocBidNumber' => 'lotBidNumber'])->alias('lot_documents');
    }
    public function getDocuments()
    {
        return $this->hasMany(Documents::className(), ['tdocBidNumber' => 'lotBidNumber'])->alias('documents');
    }
    public function getTorgs()
    {
        return $this->hasOne(Torgs::className(), ['trgBidNumber' => 'lotBidNumber'])->alias('torgs');
    }
    public function getWishlist()
    {
        return $this->hasOne(WishList::className(), ['lotId' => 'lotId'])->alias('wish')->onCondition(['type' => 'arrest']);
    }
    // Избранные
    public function WishList($user_id)
    {
        return parent::find()->alias('lot')->joinWith(['torgs', 'wishlist'])->where(['wish.userId'=>$user_id])->andWhere(['not', ['wish.id' => null]])->orderBy('torgs.trgPublished DESC');
    }
}