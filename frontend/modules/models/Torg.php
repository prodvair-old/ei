<?php

namespace frontend\modules\models;

use Yii;
use sergmoro1\lookup\models\Lookup;

/**
 * This is the model class for table "eidb.torg".
 *
 * @property int $id
 * @property int $property Тип имущества - должник, залог, арестованное, муниципальное
 * @property string|null $description Описание
 * @property int|null $started_at Назначенная дата начала торга
 * @property int|null $end_at Назначенная дата окончания торга
 * @property int|null $completed_at Дата завершения торга
 * @property int|null $published_at Дата публикации информации о торге
 * @property int $offer Тип предложения - публичное, аукцион, конкурс
 * @property int $created_at
 * @property int $updated_at
 */
class Torg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eidb.torg';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property', 'offer', 'created_at', 'updated_at'], 'required'],
            [['property', 'started_at', 'end_at', 'completed_at', 'published_at', 'offer', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['property', 'started_at', 'end_at', 'completed_at', 'published_at', 'offer', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property' => 'Тип имущества - должник, залог, арестованное, муниципальное',
            'description' => 'Описание',
            'started_at' => 'Назначенная дата начала торга',
            'end_at' => 'Назначенная дата окончания торга',
            'completed_at' => 'Дата завершения торга',
            'published_at' => 'Дата публикации информации о торге',
            'offer' => 'Тип предложения - публичное, аукцион, конкурс',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getTypeList() {
        $result['0'] = 'Все Типы';
        $result += Lookup::items('TorgProperty'); //TODO
        return $result;
    }

    public function getTorgPledge() {
        return $this->hasOne(TorgPledge::className(), ['torg_id' => 'id']);
    }
}
