<?php

namespace common\models\db;

use yii\db\ActiveRecord;

/**
 * TorgPledge model
 * Связь Торга и Залогодержателя.
 *
 * @var integer $torg_id
 * @var integer $owner_id
 * @var integer $user_id
 * 
 */
class TorgPledge extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%torg_pledge}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['torg_id', 'user_id'], 'required'],
            [['torg_id', 'user_id'], 'integer'],
            ['owner_id', 'safe'],
        ];
    }
}
