<?php
namespace common\models\db;

/**
 * Manager model
 * Управляющий, ответственный за ведение дел по банкротному имуществу.
 *
 * @property integer $id
 * @property integer $who
 * @property integer $created_at
 * @property integer $updated_at
 */
class Manager extends BaseAgent
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%manager}}';
    }
}
