<?php
namespace common\models\Query\Zalog;

use Yii;
use yii\db\ActiveRecord;

use common\models\Query\LotsCategory;
use common\models\Query\Zalog\lotCategorys;

class LotsZalogUpdate extends ActiveRecord
{
    public static function tableName()
    {
        return 'zlg."lots"';
    }
    public function getLotUrl() {
        if (!$this->status) {
            return 'javascript:void(0);';
        }
        if ($this->categorys) {
            $items = LotsCategory::find()->all();
            foreach ($items as $value) {
                foreach ($this->categorys as $category) {
                    if ($value->zalog_categorys[$category->categoryId]['translit'] !== null) {
                        return 'zalog/'.$value->translit_name.'/'.$category->categoryTranslitName.'/'.$this->id;
                    }
                }
            }
        } else {
            return 'javascript:void(0);';
        }
    }
    // public function rules()
    // {
    //     return [
    //         [['description', 'address', 'paymentDetails', 'additionalConditions', 'lotId'], 'string'],
    //         [['title'], 'string', 'max' => 150],
    //         [['country', 'city'], 'string', 'max' => 100],
    //         [['tradeType'], 'string', 'max' => 30],

    //         [['startingPrice', 'step', 'stepCount', 'tradeTipeId', 'collateralPrice', 'contactPersonId', 'ownerId'], 'integer'],

    //         [['publicationDate', 'startingDate', 'endingDate', 'completionDate', 'procedureDate', 'conclusionDate', 'viewInfo'], 'string'],

    //         [['status', 'images', 'categoryIds'], 'exist']
    //     ];
    // }
    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'ID'),
            'lotId'             => Yii::t('app', 'LotId'),
            'categoryIds'       => Yii::t('app', 'CategoryIds'),
            'description'       => Yii::t('app', 'Description'),
            'address'           => Yii::t('app', 'Address'),
            'title'             => Yii::t('app', 'Title'),
            'country'           => Yii::t('app', 'Country'),
            'city'              => Yii::t('app', 'City'),
            'tradeType'         => Yii::t('app', 'TradeType'),
            'tradeTipeId'       => Yii::t('app', 'TradeTipeId'),
            'startingPrice'     => Yii::t('app', 'StartingPrice'),
            'step'              => Yii::t('app', 'Step'),
            'stepCount'         => Yii::t('app', 'StepCount'),
            'publicationDate'   => Yii::t('app', 'PublicationDate'),
            'startingDate'      => Yii::t('app', 'StartingDate'),
            'endingDate'        => Yii::t('app', 'EndingDate'),
            'completionDate'    => Yii::t('app', 'CompletionDate'),
            'procedureDate'     => Yii::t('app', 'ProcedureDate'),
            'conclusionDate'    => Yii::t('app', 'ConclusionDate'),
            'viewInfo'          => Yii::t('app', 'ViewInfo'),
            'collateralPrice'   => Yii::t('app', 'CollateralPrice'),
            'paymentDetails'    => Yii::t('app', 'PaymentDetails'),
            'status'            => Yii::t('app', 'Status'),
            'images'            => Yii::t('app', 'Images'),
            'additionalConditions'  => Yii::t('app', 'AdditionalConditions')
        ];
    }
    public function getCategorys()
    {
        return $this->hasMany(lotCategorys::className(), ['lotId' => 'id'])->alias('categorys');
    }
}