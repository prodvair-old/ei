<?php

namespace backend\models\Editors;

use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "eiLot.owners".
 *
 * @property int $id ID владельца или организации 
 * @property string|null $createdAt Дата и время добавления записи 
 * @property string|null $updatedAt Дата и время последнего изменения записи 
 * @property string $type Тип владельца. Вид организации.
 * @property int $typeId ID типа владельца
 * @property string $title Название организации владельца
 * @property string $url URL на сайт владельца
 * @property string $logo Ссылка на логотип
 * @property string $description Описание владельца
 * @property string $email E-Mail адрес владельца
 * @property string $phone Номер телефона владельца
 * @property string $linkEi Ссылка транслит для сайта ei.ru
 * @property string $address Полный адрес владельца
 * @property string|null $inn ИНН владельца
 * @property string|null $info Дополнительная информация о владельце
 * @property int $checked Проверен ли владелец
 * @property int|null $regionId Регион владельца
 * @property string|null $template Параметры для персонализации страницы владельца
 * @property string|null $city
 * @property string|null $district
 */
class OwnerrEditor extends \yii\db\ActiveRecord
{
    public $bg;
    public $upload;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.owners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdAt', 'updatedAt', 'info', 'template'], 'safe'],
            [['title', 'url', 'description', 'email', 'phone', 'linkEi', 'address'], 'required'],
            [['regionId'], 'default', 'value' => null],
            [['typeId', 'checked'], 'default', 'value' => 1],
            [['typeId', 'checked', 'regionId'], 'integer'],
            [['title', 'url', 'logo', 'description', 'email', 'phone', 'linkEi', 'address', 'inn', 'city', 'district'], 'string'],
            [['type'], 'string', 'max' => 50],
            [['upload','bg'], 'file', 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID владельца или организации',
            'createdAt' => 'Дата и время добавления записи ',
            'updatedAt' => 'Дата и время последнего изменения записи ',
            'type' => 'Тип владельца.',
            'typeId' => 'ID типа владельца',
            'title' => 'Название организации',
            'url' => 'URL на сайт',
            'logo' => 'Логотип',
            'description' => 'Описание',
            'email' => 'E-Mail',
            'phone' => 'Номер телефона',
            'linkEi' => 'Ссылка для сайта ei.ru',
            'address' => 'Полный адрес',
            'inn' => 'ИНН',
            'info' => 'Дополнительная информация о владельце',
            'checked' => 'Проверен ли владелец',
            'regionId' => 'Регион владельца',
            'template' => 'Параметры для персонализации страницы владельца',
            'city' => 'City',
            'district' => 'District',
        ];
    }

    public function uploadBg()
    {
        $pathImage = Yii::getAlias('@frontendWeb').'/img/owner/'.$this->id.'/';

        FileHelper::createDirectory($pathImage);

        $this->bg->saveAs($pathImage.'bg-fon.'.$this->bg->getExtension());

        $template = $this->template;
        $template['bg'] = '/img/owner/'.$this->id.'/bg-fon.'.$this->bg->getExtension();
        $this->template = $template;
    }
    public function uploadLogo()
    {
        $pathImage = Yii::getAlias('@frontendWeb').'/img/owner/'.$this->id.'/';

        FileHelper::createDirectory($pathImage);

        $this->upload->saveAs($pathImage.'logo.'.$this->upload->getExtension());

        $this->logo = '/img/owner/'.$this->id.'/logo.'.$this->upload->getExtension();
    }
}
