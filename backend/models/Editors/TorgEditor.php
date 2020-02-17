<?php

namespace backend\models\Editors;

use Yii;

/**
 * This is the model class for table "eiLot.torgs".
 *
 * @property int $id ID торга
 * @property int|null $publisherId ID кем опубликован торг.
 * @property int|null $ownerId ID владельца торга.
 * @property int|null $etpId ID торговой площадки.
 * @property string $msgId Номер сообщения
 * @property string $type Вид имущества. Арестованное, банкротом или залоговое имущество
 * @property int $typeId ID вида имущества.
 * @property string|null $createdAt Дата и время добавления записи
 * @property string|null $updatedAt Дата и время последнего изменения записи
 * @property string|null $description Описание торга
 * @property string|null $startDate Дата и время начала приёма заявки
 * @property string|null $endDate Дата и время окончания приёма заявки.
 * @property string|null $completeDate Дата и время завершения торгов
 * @property string|null $publishedDate Дата и время публикации лота.
 * @property string $tradeType Тип лота. Публичное предложение или открытый аукцион.
 * @property int $tradeTypeId ID типа лота
 * @property string|null $info Дополнительная информация по лоту в виде json объектов.
 * @property int|null $bankruptId
 * @property int|null $caseId
 * @property int|null $oldId
 */
class TorgEditor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.torgs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['publisherId', 'ownerId', 'etpId', 'typeId', 'tradeTypeId', 'bankruptId', 'caseId', 'oldId'], 'default', 'value' => null],
            [['publisherId', 'ownerId', 'etpId', 'typeId', 'tradeTypeId', 'bankruptId', 'caseId', 'oldId'], 'integer'],
            [['msgId', 'type', 'typeId', 'tradeType', 'tradeTypeId'], 'required'],
            [['msgId', 'description'], 'string'],
            [['createdAt', 'updatedAt', 'startDate', 'endDate', 'completeDate', 'publishedDate', 'info'], 'safe'],
            [['type', 'tradeType'], 'string', 'max' => 50],
            [['bankruptId'], 'exist', 'skipOnError' => true, 'targetClass' => BankruptEditor::className(), 'targetAttribute' => ['bankruptId' => 'id']],
            [['caseId'], 'exist', 'skipOnError' => true, 'targetClass' => CaseEditor::className(), 'targetAttribute' => ['caseId' => 'id']],
            [['etpId'], 'exist', 'skipOnError' => true, 'targetClass' => EtpEditor::className(), 'targetAttribute' => ['etpId' => 'id']],
            [['publisherId'], 'exist', 'skipOnError' => true, 'targetClass' => ManagerEditor::className(), 'targetAttribute' => ['publisherId' => 'id']],
            [['ownerId'], 'exist', 'skipOnError' => true, 'targetClass' => OwnerrEditor::className(), 'targetAttribute' => ['ownerId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID торга',
            'publisherId' => 'ID публикатора торга',
            'ownerId' => 'ID владельца торга',
            'etpId' => 'ID торговой площадки',
            'msgId' => 'Номер сообщения',
            'type' => 'Вид имущества',
            'typeId' => 'ID вида имущества',
            'createdAt' => 'Дата и время добавления записи',
            'updatedAt' => 'Дата и время последнего изменения записи',
            'description' => 'Описание торга',
            'startDate' => 'Дата и время начала приёма заявки',
            'endDate' => 'Дата и время окончания приёма заявки',
            'completeDate' => 'Дата и время завершения торгов',
            'publishedDate' => 'Дата и время публикации лота',
            'tradeType' => 'Тип лота',
            'tradeTypeId' => 'ID типа лота',
            'info' => 'Дополнительная информация по лоту в виде json объектов',
            'bankruptId' => 'Bankrupt ID',
            'caseId' => 'Case ID',
            'oldId' => 'Old ID',
        ];
    }
}
