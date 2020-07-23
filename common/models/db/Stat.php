<?php
namespace common\models\db;

use Yii;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

use common\models\db\User;

/**
 * Stat model
 * Application statistic values are stored in a JSON array with the specified names.
 * 
 * @var string  $sid array name
 * @var text    $defs JSON array definition
 * @var integer $duration actuality period of a current values
 * @var integer $updated_at last updated 
 */
class Stat extends ActiveRecord
{
    private static $namespace_prefix = '\common\jobs\Stat';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%stat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sid', 'defs'], 'required'],
            [['sid'], 'string', 'max' => 255],
            ['duration', 'integer'],
            ['duration', 'default', 'value' => 36000],
            [['defs', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sid'        => Yii::t('app', 'Name'),
            'defs'       => Yii::t('app', 'Definitions'),
            'duration'   => Yii::t('app', 'duration'),
            'updated_at' => Yii::t('app', 'Updated_at'),
        ];
    }

    /**
     * Get an array from a JSON definition found by a string ID.
     * String ID can be user dependent. In this case it consists of two parts.
     * ID plus user_id.
     * 
     * @param string  $sid
     * @param boolean $user_dependent
     * @return array with structure and values as defined in migration
     * @see common/migrations/*_stat.php
     */
    public function getDefs($sid, $user_dependent)
    {
        if ($model = self::findOne(['sid' => $sid])) {
            $class = self::$namespace_prefix . ucfirst($sid) . 'Job';
            if ($user_id = ($user_dependent && in_array(Yii::$app->user->identity->role, [User::ROLE_AGENT, User::ROLE_ARBITRATOR]))
                ? Yii::$app->user->id : false) {
                // make user dependent $sid
                $udsid = $sid . '_' . $user_id;
                // try to find user dependent row, if not then add a new one with a key = $sid . $user_id
                if ($next = self::findOne(['sid' => $udsid]))
                    $model = $next;
                else {
                    // set all values to 'n/a'
                    $vars = Json::decode($model->defs);
                    foreach($vars as $key => $var)
                        $vars[$key]['value'] = 'n/a';
                    // init new model and save it
                    $next = new Stat([
                        'sid'        => $udsid,
                        'defs'       => Json::encode($vars),
                        'duration'   => $model->duration,
                        'updated_at' => $model->updated_at,
                    ]);
                    $next->save(false);
                    $model = $next;
                }
            }
            if (($model->updated_at + $model->duration) < time()) {
                // calculate in a queue
                $class = '\common\jobs\Stat' . ucfirst($sid) . 'Job';
                Yii::$app->queue->push(new $class([
                    'sid'     => $sid,
                    'user_id' => $user_id,
                ]));
            }
            return Json::decode($model->defs);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested model does not exist.'));
        }
    }
    
    /**
     * If the value is a number, format it otherwise return the value as is.
     * 
     * @param string $value
     * @return string
     */
    public static function format($value)
    {
        return is_numeric($value) ? number_format(floor($value), 0, '', ' ') : $value;
    }
}
