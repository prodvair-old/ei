<?php

namespace backend\models\Editors;

use Yii;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * This is the model class for table "eiLot.lots".
 *
 * @property int $id ID лота
 * @property int $torgId ID торга по лоту
 * @property string $msgId Номер или id сообщения лота
 * @property int|null $lotNumber Номер лота в торге
 * @property string|null $createdAt Дата и время добавления записи
 * @property string|null $updatedAt Дата и время последнего изменения записи
 * @property string $title Заголовок лота
 * @property string $description Описание лота
 * @property float $startPrice Начальная цена лота
 * @property float|null $step Шаг аукциона
 * @property string|null $stepType Тип шага торга. Может принимать "процент" или "сумма". Зависит от stepTypeId
 * @property int|null $stepTypeId ID типа шага торга. Может принимать "1" или "2". Зависит от stepType
 * @property float|null $deposit Задаток для участия в торге.
 * @property string|null $depositType Тип цены задатка для участия в торге. Может принимать "процент" или "сумма". Зависит от depositTypeId
 * @property int|null $depositTypeId ID типа цены задатка для участия в торге. Может принимать "1" или "2". Зависит от depositType
 * @property string $status Стату лота на торгах.
 * @property string $info Дополнительная информация о лота в виде json объектов
 * @property string|null $images Картинки лота в виде массива объектов
 * @property bool $published Лот опубликован на сайте или нет
 * @property int|null $regionId ID Региона где находится этот лот
 * @property string|null $city
 * @property string|null $district
 * @property int|null $oldId
 * @property int|null $bankId
 */
class LotEditor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.lots';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['torgId', 'msgId', 'title', 'description', 'startPrice', 'status', 'info'], 'required'],
            [['torgId', 'lotNumber', 'stepTypeId', 'depositTypeId', 'regionId', 'oldId', 'bankId'], 'default', 'value' => null],
            [['torgId', 'lotNumber', 'stepTypeId', 'depositTypeId', 'regionId', 'oldId', 'bankId'], 'integer'],
            [['msgId', 'title', 'description', 'city', 'district'], 'string'],
            [['createdAt', 'updatedAt', 'info'], 'safe'],
            ['images', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
            [['startPrice', 'step', 'deposit'], 'number'],
            [['published'], 'boolean'],
            [['stepType', 'depositType'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 70],
            [['bankId'], 'exist', 'skipOnError' => true, 'targetClass' => BankEditor::className(), 'targetAttribute' => ['bankId' => 'id']],
            [['torgId'], 'exist', 'skipOnError' => true, 'targetClass' => TorgEditor::className(), 'targetAttribute' => ['torgId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID лота',
            'torgId' => 'ID торга по лоту',
            'msgId' => 'Номер сообщения лота (Не стоит изменять)',
            'lotNumber' => 'Номер лота в торге (Не стоит изменять)',
            'createdAt' => 'Дата и время добавления записи',
            'updatedAt' => 'Дата и время последнего изменения записи',
            'title' => 'Заголовок лота',
            'description' => 'Описание лота',
            'startPrice' => 'Начальная цена лота',
            'step' => 'Шаг аукциона',
            'stepType' => 'Тип шага торга. Может принимать \"процент\" или \"сумма\". Зависит от stepTypeId',
            'stepTypeId' => 'Тип шага торга.',
            'deposit' => 'Задаток для участия в торге.',
            'depositType' => 'Тип цены задатка для участия в торге. Может принимать \"процент\" или \"сумма\". Зависит от depositTypeId',
            'depositTypeId' => 'Тип цены задатка для участия.',
            'status' => 'Стату лота на торгах.',
            'info' => 'Дополнительная информация о лота в виде json объектов',
            'images' => 'Загрузка картинкок',
            'published' => 'Лот опубликован на сайте или нет',
            'regionId' => 'ID Региона где находится этот лот',
            'city' => 'City',
            'district' => 'District',
            'oldId' => 'Old ID',
            'bankId' => 'Bank ID',
        ];
    }

    public function uploadImages()
    {
        $images = [];

        foreach ($this->images as $id => $image) {

            $pathImage = '/img/lot/'.$this->id.'/';
            $format = $image->extension;

            $frontParth = Yii::getAlias('@frontendWeb').$pathImage;

            FileHelper::createDirectory($frontParth);

            $image->saveAs($frontParth.'lot_photo_'.$id.'.'.$format);

            $image = Yii::getAlias($frontParth.'lot_photo_'.$id.'.'.$format);
            $sizeText = getimagesize($image); // Определяем размер картинки
            $imageWidth = $sizeText[0]; // Ширина картинки

            if ($imageWidth >= 400) {
                $imageHeight = $sizeText[1]; // Высота картинки
                $textPositionLeft = $imageWidth - 400; // Новая позиция watermark по оси X (горизонтально)
                $textPositionTop = $imageHeight - 31;  // Новая позиция watermark по оси Y (вертикально)
                $text = Yii::getAlias('@backendWeb').'/img/wathertext.jpg'; // 200x200
                
                Image::watermark($image, $text, [$textPositionLeft, $textPositionTop])
                    ->save(Yii::getAlias($frontParth.'lot_photo_max_'.$id.'.'.$format));

                $resizeImg = Yii::getAlias($frontParth.'lot_photo_max_'.$id.'.'.$format);

                Image::getImagine()->open($resizeImg)
                    ->thumbnail(new Box(1280, 1280))
                    ->save(Yii::getAlias($frontParth.'lot_photo_min_'.$id.'.'.$format) , ['quality' => 90]);

                $images[] = [
                    'max' => $pathImage.'lot_photo_max_'.$id.'.'.$format,
                    'min' => $pathImage.'lot_photo_min_'.$id.'.'.$format,
                ];
            } else {
                Yii::$app->session->setFlash('warning', "У картинки под №$id ширина меньше 400px. ПОжалуйста загрузите картинку по больше");
            }
        }

        $this->images = $images;
        return true;
    }
}
