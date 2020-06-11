<?php

namespace frontend\modules\models;

use Yii;

/**
 * This is the model class for table "onefile".
 *
 * @property int $id
 * @property string $model Model namespace
 * @property int $parent_id Model ID
 * @property string $original Translited file name
 * @property string $name Generated unique file name
 * @property string $subdir Subdirectory in a model directory, may be various from model to model or the same
 * @property string $type Mime type
 * @property int $size Size
 * @property string|null $defs Additional variables linked with file are saved as json array
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Onefile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%onefile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'parent_id', 'original', 'name', 'subdir', 'type', 'size'], 'required'],
            [['parent_id', 'size', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['parent_id', 'size', 'created_at', 'updated_at'], 'integer'],
            [['defs'], 'string'],
            [['model', 'original', 'subdir', 'type'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model namespace',
            'parent_id' => 'Model ID',
            'original' => 'Translited file name',
            'name' => 'Generated unique file name',
            'subdir' => 'Subdirectory in a model directory, may be various from model to model or the same',
            'type' => 'Mime type',
            'size' => 'Size',
            'defs' => 'Additional variables linked with file are saved as json array',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
