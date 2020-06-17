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
}

