<?php

namespace common\models\db;

use yii\db\ActiveRecord;

/**
 * TorgDebtor model
 * Связь Торга и Банкрота.
 *
 * @var integer $torg_id
 * @var integer $bankrupt_id
 * @var integer $manager_id
 * @var integer $case_id
 * 
 */
class TorgDebtor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%torg_debtor}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['torg_id', 'required'],
            [['bankrupt_id', 'manager_id', 'case_id'], 'safe'],
        ];
    }
}
