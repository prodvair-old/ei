<?php

namespace backend\models\Editors;

use Yii;

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
        $this->images = UploadedFile::getInstances($this, 'images');

        foreach ($this->lot_images as $file) {

            $check = Yii::$app->db->createCommand('select id from obj$images where fileurl = \'lot_photo_'.$maxid.'.'.$file->extension.'\'')->queryAll();

            if ($check[0] == null) {

                $frontParth = Yii::getAlias('@frontendWeb').'/img/lot/'.$this->lot_id.'/';

                FileHelper::createDirectory($frontParth);

                $file->saveAs($frontParth.'lot_photo_'.$maxid.'.'.$file->extension);

                $resizeImg = Yii::getAlias($frontParth.'lot_photo_'.$maxid.'.'.$file->extension);

                Image::getImagine()->open($resizeImg)
                    ->thumbnail(new Box(1280, 1280))
                    ->save(Yii::getAlias($frontParth.'lot_photo_'.$maxid.'.'.$file->extension) , ['quality' => 90]);

                $image = Yii::getAlias($frontParth.'lot_photo_'.$maxid.'.'.$file->extension);
                $sizeText = getimagesize($image); // Определяем размер картинки
                $imageWidth = $sizeText[0]; // Ширина картинки
                if ($imageWidth < 400) {
                    return false;
                }
                $imageHeight = $sizeText[1]; // Высота картинки
                $textPositionLeft = $imageWidth - 400; // Новая позиция watermark по оси X (горизонтально)
                $textPositionTop = $imageHeight - 31;  // Новая позиция watermark по оси Y (вертикально)
                $text = Yii::getAlias('@backendWeb').'/img/wathertext.jpg'; // 200x200
                
                Image::watermark($image, $text, [$textPositionLeft, $textPositionTop])
                    ->save(Yii::getAlias($frontParth.'lot_photo_'.$maxid.'.'.$file->extension));

                // $imageWather = Yii::getAlias($frontParth.'lot_photo_'.$maxid.'.'.$file->extension);
                // $sizeImg = getimagesize($imageWather); // Определяем размер картинки
                // $imageWatherWidth = $sizeImg[0]; // Ширина картинки
                // $imageWatherHeight = $sizeImg[1]; // Высота картинки

                // $watherImg = Yii::getAlias(Yii::getAlias('@backendWeb').'/img/watermark.png');
                // $sizeWather = getimagesize($watherImg); // Определяем размер картинки
                // $watherWidth = $sizeWather[0]; // Ширина картинки
                // $watherHeight = $sizeWather[1]; // Высота картинки

                // $watermarkPositionLeft = ($imageWatherWidth * 0.5) - ($watherWidth * 0.5); // Новая позиция watermark по оси X (горизонтально)
                // $watermarkPositionTop = ($imageWatherHeight * 0.5) - ($watherHeight * 0.5);  // Новая позиция watermark по оси Y (вертикально)
                // $watermark = Yii::getAlias(Yii::getAlias('@backendWeb').'/img/watermark.png'); // 200x200
                
                // Image::watermark($imageWather, $watermark, [$watermarkPositionLeft, $watermarkPositionTop])
                //     ->save(Yii::getAlias($frontParth.'lot_photo_'.$maxid.'.'.$file->extension));]
            }
        }
        return true;
    }
}
