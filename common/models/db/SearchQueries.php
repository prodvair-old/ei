<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use frontend\modules\models\Category;

/**
 * SearchQueries model
 * Сохранённые поисковые запросы, отмеченные пользователем.
 * 
 * @var integer $id
 * @var integer $user_id
 * @var string $defs
 * @var string $descripton
 * @var string $url
 * @var integer $seached_at
 * @var integer $last_count
 * @var boolean $send_email
 * @var integer $created_at
 * @var integer $updated_at
 */
class SearchQueries extends ActiveRecord
{
    // сценарии
    const SCENARIO_CREATE = 'search_queries_create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%search_queries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_id', 'required', 'except' => self::SCENARIO_CREATE],
            [['user_id', 'defs', 'url', 'seached_at'], 'required'],
            [['defs', 'url', 'descripton'], 'string'],
            [['last_count'], 'integer'],
            ['last_count', 'default', 'value' => 0],
            ['send_email', 'boolean'],
            ['send_email', 'default', 'value' => true],
            [['seached_at', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id'       => Yii::t('app', 'Пользователь'),
            'defs'          => Yii::t('app', 'Определения поиска'),
            'descripton'    => Yii::t('app', 'Описание'),
            'url'           => Yii::t('app', 'Полная ссылка'),
            'seached_at'    => Yii::t('app', 'Дата последнего поиска'),
            'last_count'    => Yii::t('app', 'Количество найденных лотов'),
            'send_email'    => Yii::t('app', 'Send Email'),
            'created_at'    => Yii::t('app', 'Created'),
            'updated_at'    => Yii::t('app', 'Modified'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
    
            $this->updated_at = strtotime((new \DateTime())->format('Y-m-d H:i:s'));
    
            return true;
        }
        return false;
    }

    /**
     * Generate Name
     *
     * @var string $url
     * 
     * @return array parsing GET parameters from a link and making name for search
     */
    public function getFirstSave()
    {
        $data = $this->getQueryParser();

        switch ($data['path'][0]) {
            case 'bankrupt':
                $defs = 'Банкротное имущество';
                $n = 1;
                break;
            case 'arrest':
                $defs = 'Арестованное имущество';
                $n = 2;
                break;
            case 'zalog':
                $defs = 'Имущество организации';
                $n = 3;
                break;
            case 'municipal':
                $defs = 'Муниципальное имущество';
                $n = 4;
                break;
            default:
                $defs = 'Все виды иммущества';
                break;
        }
        $data['query']['LotSearch']['type'] = $n;

        if ($data['path'][1] != null) {
            $cat = Category::find()->where(['slug' => $data['path'][1]])->one();
            $data['query'][ 'LotSearch' ][ 'mainCategory' ] = 0;

            $defs .= ' - '.$cat->name;
        }

        if ($data['query']['LotSearch']['search']) {
            $defs .= ' - <span class="font200">'.$data['query']['LotSearch']['search'].'</span>';
        }

        $descripton = '';

        if ($data['query']['LotSearch']['region']) {
            $regionNames = '';
            foreach ($data['query']['LotSearch']['region'] as $regionId) {
                $region = Region::findOne(['id' => $regionId]);
                $regionNames .= ($regionNames ? ', ' : '').$region->name;
            }
            $descripton .= 'Регион: '.$regionNames.'; ';
        }

        if ($data['query']['LotSearch']['minPrice']) {
            $descripton .= 'Цена мин.: '.$data['query']['LotSearch']['minPrice'].'; ';
        }
        if ($data['query']['LotSearch']['maxPrice']) {
            $descripton .= 'Цена макс.: '.$data['query']['LotSearch']['maxPrice'].'; ';
        }

        if ($data['query']['LotSearch']['tradeType']) {
            $tradeType = '';
            foreach ($data['query']['LotSearch']['tradeType'] as $key => $tradeTypeId) {
                if ($key !== 0) {
                    $tradeType .= ', ';
                }
                switch ($tradeTypeId) {
                    case '1':
                        $tradeType .= 'Публичное предложение';
                        break;
                    case '2':
                        $tradeType .= 'Открытый аукцион';
                        break;
                    case '3':
                        $tradeType .= 'Аукцион';
                        break;
                    case '4':
                        $tradeType .= 'Открытый конкурс';
                        break;
                    case '5':
                        $tradeType .= 'Конкурс';
                        break;
                }
            }
            
            $descripton .= 'Тип торгов: '.$tradeType.'; ';
        }

        if ($data['query']['LotSearch']['etp']) {
            $etpNames = '';
            foreach ($data['query']['LotSearch']['etp'] as $etpId) {
                $etp = Etp::findOne(['id' => $etpId]);
                $etpNames .= ($etpNames ? ', ' : '').$etp->organization->title;
            }
            $descripton .= 'Торговые площадки: '.$etpNames.'; ';
        }

        if ($data['query']['LotSearch']['subCategory']) {
            $subCategoryNames = '';
            foreach ($data['query']['LotSearch']['subCategory'] as $subCategoryId) {
                $subCategory = Category::findOne(['id' => $subCategoryId]);
                $subCategoryNames .= ($subCategoryNames ? ', ' : '').$subCategory->name;
            }
            $descripton .= 'Под категории: '.$subCategoryNames.'; ';
        }

        $date = strtotime((new \DateTime())->format('Y-m-d H:i:s'));

        $this->defs       = $defs;
        $this->descripton = $descripton;
        $this->last_count = 0;
        $this->seached_at = $date;
        $this->created_at = $date;
        return [$data['query']['LotSearch'], $defs];
    }

    /**
     * Query parser
     *
     * @var string $url
     * 
     * @return array parsing GET parameters from a link
     */
    public function getQueryParser($fullPath = false)
    {
      $query = null;

      $parts = parse_url($this->url);
      if (!$fullPath) {
        $path = explode("/", substr($parts['path'],1));
      } else {
        $path = $parts;
      }
      
      if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
      } else {
        $query = false;
      }

      return ['query' => $query, 'path' => $path];
    }
}
