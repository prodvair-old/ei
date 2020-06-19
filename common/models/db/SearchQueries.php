<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * SearchQueries model
 * Сохранённые поисковые запросы, отмеченные пользователем.
 * 
 * @var integer $id
 * @var integer $user_id
 * @var string $title
 * @var string $description
 * @var string $url
 * @var string $url_query
 * @var integer $search_date
 * @var integer $search_lot_count
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
            [['title','description', 'url', 'url_query', 'search_date', 'search_lot_count'], 'required'],
            ['title', 'string', 'max' => 255],
            [['description', 'url', 'url_query'], 'string'],
            [['search_date', 'search_lot_count'], 'integer'],
            ['search_lot_count', 'default', 'value' => 0],
            ['send_email', 'boolean'],
            ['send_email', 'default', 'value' => true],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id'           => Yii::t('app', 'Пользователь'),
            'title'             => Yii::t('app', 'Название'),
            'description'       => Yii::t('app', 'Описание'),
            'url'               => Yii::t('app', 'Полная ссылка'),
            'url_query'         => Yii::t('app', 'GET запрос'),
            'search_date'       => Yii::t('app', 'Дата последнего поиска'),
            'search_lot_count'  => Yii::t('app', 'Количество найденных лотов'),
            'send_email'        => Yii::t('app', 'Send Email'),
            'created_at'        => Yii::t('app', 'Created'),
            'updated_at'        => Yii::t('app', 'Modified'),
        ];
    }
}
