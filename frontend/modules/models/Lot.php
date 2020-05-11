<?php

namespace frontend\modules\models;

use Yii;

/**
 * This is the model class for table "eidb.lot".
 *
 * @property int $id
 * @property int $torg_id Торг, к которому принадлежит лот
 * @property string $title Заголовок лота, как правило это первые слова Описания
 * @property string $description Описание
 * @property float $start_price Начальная цена
 * @property float $step Шаг уменьшения цены
 * @property int $step_measure Мера шага - сумма, %
 * @property float $deposite Размер задатка за лот
 * @property int $deposite_measure Мера задатка - сумма, %
 * @property int $status Статус
 * @property int $reason Причина статуса
 * @property int $created_at
 * @property int $updated_at
 */
class Lot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eidb.lot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['torg_id', 'title', 'description', 'start_price', 'step', 'step_measure', 'deposite', 'deposite_measure', 'status', 'reason', 'created_at', 'updated_at'], 'required'],
            [['torg_id', 'step_measure', 'deposite_measure', 'status', 'reason', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['torg_id', 'step_measure', 'deposite_measure', 'status', 'reason', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['start_price', 'step', 'deposite'], 'number'],
            [['title'], 'string', 'max' => 255],
            [['torg_id'], 'exist', 'skipOnError' => true, 'targetClass' => Torg::className(), 'targetAttribute' => ['torg_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'torg_id' => 'Торг, к которому принадлежит лот',
            'title' => 'Заголовок лота, как правило это первые слова Описания',
            'description' => 'Описание',
            'start_price' => 'Начальная цена',
            'step' => 'Шаг уменьшения цены',
            'step_measure' => 'Мера шага - сумма, %',
            'deposite' => 'Размер задатка за лот',
            'deposite_measure' => 'Мера задатка - сумма, %',
            'status' => 'Статус',
            'reason' => 'Причина статуса',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
