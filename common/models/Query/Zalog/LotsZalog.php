<?php
namespace common\models\Query\Zalog;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

use common\models\Query\User;
use common\models\Query\WishList;
use common\models\Query\PageViews;
use common\models\Query\LotsCategory;
use common\models\Query\Zalog\lotCategorys;
use common\models\Query\Zalog\OwnerProperty;

// Таблица лотов залогового
class LotsZalog extends ActiveRecord
{
    public static function tableName()
    {
        return 'zlg.{{lots}}';
    }
    public function rules()
    {
        return [
            [[
                'description', 'address', 'title', 'country', 'city', 
                'tradeType', 'startingPrice', 'step', 'stepCount', 'tradeTipeId', 
                'publicationDate', 'startingDate', 'endingDate', 'completionDate', 
                'procedureDate', 'conclusionDate', 'viewInfo', 'collateralPrice',
                'paymentDetails', 'additionalConditions', 'lotId', 'contactPersonId',
                'ownerId', 'categoryIds', 'subCategory',
            ], 'required'],

            [['description', 'address', 'paymentDetails', 'additionalConditions', 'lotId', 'currentPeriod'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['country', 'city'], 'string', 'max' => 100],
            [['tradeType'], 'string', 'max' => 30],

            [['startingPrice', 'step', 'stepCount', 'tradeTipeId', 'collateralPrice', 'contactPersonId', 'ownerId'], 'number'],

            [['publicationDate', 'startingDate', 'endingDate', 'completionDate', 'procedureDate', 'conclusionDate', 'viewInfo'], 'string'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),
            'lotId'             => Yii::t('app', 'Лот id'),
            'categoryIds'       => Yii::t('app', 'Категории'),
            'description'       => Yii::t('app', 'Описание'),
            'address'           => Yii::t('app', 'Адрес'),
            'title'             => Yii::t('app', 'Заголовок'),
            'country'           => Yii::t('app', 'Страна'),
            'city'              => Yii::t('app', 'Город'),
            'tradeType'         => Yii::t('app', 'Тип тогов'),
            'tradeTipeId'       => Yii::t('app', 'Id типа торгов'),
            'startingPrice'     => Yii::t('app', 'Начальная цена'),
            'step'              => Yii::t('app', 'Шаг'),
            'stepCount'         => Yii::t('app', 'Количество шагов'),
            'publicationDate'   => Yii::t('app', 'Дата пуликации'),
            'startingDate'      => Yii::t('app', 'Дата начала торгов'),
            'endingDate'        => Yii::t('app', 'Дата окончания приёма заявок'),
            'completionDate'    => Yii::t('app', 'Дата завершения торгов'),
            'procedureDate'     => Yii::t('app', 'ProcedureDate'),
            'conclusionDate'    => Yii::t('app', 'ConclusionDate'),
            'viewInfo'          => Yii::t('app', 'ViewInfo'),
            'collateralPrice'   => Yii::t('app', 'CollateralPrice'),
            'paymentDetails'    => Yii::t('app', 'PaymentDetails'),
            'currentPeriod'     => Yii::t('app', 'CurrentPeriod'),
            'additionalConditions'  => Yii::t('app', 'AdditionalConditions')
        ];
    }
    public function getLotTitle() 
    {
        return $this->title;
    }
    public function getLotUrl() {
        if ($this->categorys) {
            $items = LotsCategory::find()->all();
            foreach ($items as $value) {
                foreach ($this->categorys as $category) {
                    if ($value->zalog_categorys[$category->categoryId]['translit'] !== null) {
                        return $this->owner->linkForEi.'/'.$value->translit_name.'/'.$category->categoryTranslitName.'/'.$this->id;
                    }
                }
            }
        } else {
            return 'javascript:void(0);';
        }
    }
    public function getLot_archive()
    {
        $today = new \DateTime();
        if ($this->completionDate == null) {
            return false;
        } else if (strtotime($this->completionDate) <= strtotime($today->format('Y-m-d H:i:s'))) {
            return true;
        } else {
            return false;
        }
    }
    public function getLot_timepublication() 
    {
        return $this->publicationDate;
    }
    public function getLotImage()
    {
        if ($this->images) {
            foreach ($this->images as $image) {
                $images[] = $image['min'];
            }
            return $images;
        } else {
            return null;
        }
    }
    public function getLotDateEnd() 
    {
        return $this->endingDate;
    }
    public function getLotDateStart() 
    {
        return $this->startingDate;
    }
    public function getLotPrice() 
    {
        return $this->startingPrice;
    }
    public function getLotOldPrice() 
    {
        return null;
    }
    public function getLotType()
    {
        return 'zalog';
    }
    public function getLotCadastre() 
    {
        $kadastr_check = preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{6,7}:[0-9]{1,35}/", $this->description, $kadastr);
        return ($kadastr_check)? $kadastr[0] : false;
    }
    public function getLotVin() 
    {
        $vin_text = str_replace('VIN', '',$this->description);
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
        foreach ($this->categorys as $key => $value) {
            $categorys[] = $value->categoryName;
        }
        return $categorys;
    }
    
    public function fields()
    {
        return [
            // 'lot_id'            => 'lotId',
            // 'lot_description'   => 'lotPropName',
            // 'lot_start_price'   => 'lotStartPrice',
            // 'lot_step_price'    => 'lotPriceStep',
            // 'lot_advance'       => 'lotDepositSize',
            // 'lot_address'       => 'lotKladrLocationName',
            // // Другие поля для поиска
            // 'lot_title'         => 'lotTitle',
            // 'lot_images'        => 'lotImage',
            // 'lot_publication'   => 'lotPublication',
            // 'lot_date_end'      => 'lotDateEnd',
            // 'lot_date_start'    => 'lotDateStart',
            // 'lot_price'         => 'lotPrice',
            // 'lot_category'      => 'lotPropertyTypeName',
            // 'lot_cadastre'      => 'lotCadastre',
            // 'lot_vin'           => 'lotVin',
            // 'lot_category_translit' => 'lotCategoryTranslit',
            // 'wish_id'           => 'lotWishId',
            // 'lotType'           => 'lotType'

        ];
    }
    public function getCategorys()
    {
        return $this->hasMany(lotCategorys::className(), ['lotId' => 'id'])->alias('categorys');
    }
    public function getOwner()
    {
        return $this->hasOne(OwnerProperty::className(), ['id' => 'ownerId'])->alias('owner');
    }
    public function getAgent()
    {
        return $this->hasOne(User::className(), ['id' => 'contactPersonId'])->alias('agent');
    }
    public function getViews()
    {
        return $this->hasMany(PageViews::className(), ['page_id' => 'id'])->alias('views')->onCondition(['page_type' => 'lot_zalog']);
    }
    public function getWishlist()
    {
        return $this->hasOne(WishList::className(), ['lotId' => 'id'])->alias('wish')->onCondition(['type' => 'zalog']);
    }
    // public function WishCount($limit = null, $offset = null)
    // {
    //     return parent::find()->select('count(wish.id)')->alias('lot')->joinWith(['torgs', 'wishlist'])->orderBy('torgs.trgPublished DESC'))['count'];
    // }
}