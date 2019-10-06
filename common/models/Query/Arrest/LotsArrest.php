<?php
namespace common\models\Query\Arrest;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

use common\models\Query\Arrest\Documents;
use common\models\Query\Arrest\LotDocuments;
use common\models\Query\WishList;

// Таблица лотов арестовки
class LotsArrest extends ActiveRecord
{
    public static function tableName()
    {
        return 'bailiff.{{lots}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function getLotTitle() 
    {
        return (strlen($this->lotPropName) < 140)? $this->lotPropName : mb_substr($this->lotPropName, 0, 130, 'UTF-8').'...';
    }
    public function getLotPublication() 
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
    public function getLotCategoryTranslit() {
        $categorys = explode(';',$this->lotPropertyTypeName);

        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',   '(' => '',     ')' => '',
            ',' => '',     '.' => '',     '-' => '',     ';' => '/',
        );

        $category = mb_strtolower($categorys[0]);
        $category = strtr($category, $converter);
        $category = mb_ereg_replace('[^-0-9a-z]', '-', $category);
        $category = mb_ereg_replace('[-]+', '-', $category);
        $category = trim($category, '-');
        $subCategory = mb_strtolower($categorys[1]);
        $subCategory = strtr($subCategory, $converter);
        $subCategory = mb_ereg_replace('[^-0-9a-z]', '-', $subCategory);
        $subCategory = mb_ereg_replace('[-]+', '-', $subCategory);
        $subCategory = trim($subCategory, '-');
        return "arrest/$category/$subCategory";
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
            'lot_images'        => 'LotImage',
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
        return $this->hasOne(WishList::className(), ['lotId' => 'lotId'])->alias('wish')->onCondition([
            'type' => 'arrest'
        ]);
    }
    // Избранные
    public function WishList($user_id)
    {
        return parent::find()->alias('lot')->joinWith(['torgs', 'wishlist'])->where(['wish.userId'=>$user_id])->andWhere(['not', ['wish.id' => null]])->orderBy('torgs.trgPublished DESC');
    }
}