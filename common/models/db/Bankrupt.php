<?php

namespace common\models\db;


/**
 * Bankrupt model
 * Банкрот, персона или организация.
 *
 * @var integer $id
 * @var integer $agent
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Place        $place
 * @property Profile      $profile
 * @property Organization $organization
 */
class Bankrupt extends BaseAgent
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bankrupt}}';
    }
}
