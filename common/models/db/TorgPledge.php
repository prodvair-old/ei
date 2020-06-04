<?php

namespace common\models\db;

use Yii;
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
    // сценарии
    const SCENARIO_MIGRATION = 'torg_pledge_migration';
    const SCENARIO_CREATE = 'torg_pledge_create';

    /** @var $add_lot boolean on a next step */
    public $add_lot = false;
    
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
            [['torg_id'], 'required', 'except' => self::SCENARIO_CREATE],
            [['torg_id'], 'integer'],
            [['owner_id', 'user_id'], 'required', 'except' => self::SCENARIO_MIGRATION],
            ['add_lot', 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'owner_id' => Yii::t('app', 'Owner'),
            'user_id'  => Yii::t('app', 'User'),
            'add_lot'  => Yii::t('app', 'Add a lot after saving'),
        ];
    }
}
