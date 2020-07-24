<?php
namespace common\models\db;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use yii\db\IntegrityException;
use yii\web\NotFoundHttpException;
use yii\behaviors\TimestampBehavior;

use common\models\db\Tariff;
use common\models\db\Report;
use common\models\db\User;

/**
 * Invoice model
 * An Invoice used for any type of product in the system 
 * that can be sold to the end user.
 * 
 * @var integer $id
 * @var integer $product
 * @var integer $parent_id
 * @var integer $user_id
 * @var text    $info
 * @var integer $sum
 * @var boolean $paid
 * @var integer $cteated_at 
 */
class Invoice extends ActiveRecord
{
    // values of enumerated variables
    const PRODUCT_TARIFF = 1;
    const PRODUCT_REPORT = 2;

    /* @var array $vars from JSON definition */
    public $vars;

    private static $paid_variants = [false => 'Not paid', true => 'Paid'];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invoice}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product', 'parent_id', 'sum'], 'required'],
            ['product', 'in', 'range' => self::getProducts()],
            [['parent_id', 'sum'], 'integer'],
            ['paid', 'boolean'],
            ['paid', 'default', 'value' => false],
            [['info', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product'    => Yii::t('app', 'Product'),
            'sum'        => Yii::t('app', 'Sum'),
            'paid'       => Yii::t('app', 'Paid'),
            'created_at' => Yii::t('app', 'Created'),
        ];
    }

    /**
     * Get products key.
     * 
     * @return array
     */
    public static function getProducts() {
        return [
            self::PRODUCT_TARIFF,
            self::PRODUCT_REPORT,
        ];
    }

    /**
     * Get number and date.
     * 
     * @return string
     */
    public function getNumberDate()
    {
        return $this->id . ' / ' . date('Y-m-d', $this->created_at);
    }

    /**
     * Get product link.
     * 
     * @return string
     */
    public function getProductLink()
    {
        switch ($this->product) {
            case self::PRODUCT_TARIFF:
                return Html::a($this->tariff->name, ['tariff/view', 'id' => $this->parent_id]);
            case self::PRODUCT_REPORT:
                return Html::a($this->report->title, ['report/view', 'id' => $this->parent_id]);
        }
    }
    
    /**
     * Get Tariff.
     * 
     * @return yii\db\ActiveRecord
     */
    public function getTariff()
    {
        if ($this->product = self::PRODUCT_TARIFF)
            return Tariff::findOne($this->parent_id);
        else
            throw IntegrityException();
    }

    /**
     * Get Report.
     * 
     * @return yii\db\ActiveRecord
     */
    public function getReport()
    {
        if ($this->product = self::PRODUCT_REPORT)
            return Report::findOne($this->parent_id);
        else
            throw IntegrityException();
    }

    /**
     * Get user.
     * 
     * @return yii\db\ActiveRecord
     */
    public function getUser()
    {
        return User::findOne($this->user_id);
    }
    
    /**
     * Get formatted sum.
     * 
     * @return string
     */
    public function getSum() {
        return number_format($this->sum, 0, '', ' ');
    }

    /**
     * Get formatted paid.
     * 
     * @return string
     */
    public function getPaid() {
        return self::$paid_variants[$this->paid];
    }

    /**
     * Get paid variants.
     * 
     * @return array
     */
    public static function getPaidVariants() {
        return self::$paid_variants;
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $vars = Json::decode($this->info);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            $this->info = Json::encode($this->vars);
            return true;
        }
        else
            return false;
    }
}
