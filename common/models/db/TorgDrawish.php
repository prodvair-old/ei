<?php

namespace common\models\db;

use yii\db\ActiveRecord;

/**
 * TorgDrawish model
 * Связь Торга и Менеджера, опубликовавшего Лот по ничейному имуществу (арестованное, муниципальное).
 *
 * @var integer $torg_id
 * @var integer $manager_id
 * 
 */
class TorgDrawish extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%torg_drawish}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['torg_id', 'manager_id'], 'required'],
            [['torg_id', 'manager_id'], 'integer'],
        ];
    }
}
