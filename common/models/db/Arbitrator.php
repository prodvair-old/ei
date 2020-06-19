<?php

namespace common\models\db;

use yii\db\ActiveRecord;

/**
 * Arbitrartor model
 * Link of User and Rbitration Manager.
 *
 * @var integer $user_id
 * @var integer $manager_id
 */
class Arbitrator extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%arbitrator}}';
    }

    /**
     * Get Manager ID by User ID
     */
    public static function getManagerIdBy($user_id)
    {
        return ($arbitrator = self::findOne($user_id)) ? $arbitrator->manager_id : 0;
    }
}

