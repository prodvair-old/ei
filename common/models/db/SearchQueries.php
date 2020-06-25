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
            [['defs', 'url'], 'string'],
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
            'defs'          => Yii::t('app', 'Описание'),
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

        $date = strtotime((new \DateTime())->format('Y-m-d H:i:s'));

        $this->defs = $defs;
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
    public function getQueryParser()
    {
      $query = null;

      $parts = parse_url($this->url);
      $path = explode("/", substr($parts['path'],1));
      parse_str($parts['query'], $query);

      return ['query' => $query, 'path' => $path];
    }
}
