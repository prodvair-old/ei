<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

use common\models\Query\Zalog\LotsZalogUpdate;

use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class UploadZalogLotImage extends Model
{ 
    public $images;
    public $lotId;

    public function rules()
    {
        return [
            [['images', 'lotId'], 'required'],
            [['images'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 20]
        ];
    }

    public function attributeLabels()
    {
        return [
            'images' => 'Картинки',
            'lotId' => 'ID лота',
        ];
    }

    public function uploadImages()
    {

        FileHelper::createDirectory(Yii::getAlias('@frontendWeb').'/img/lots/zalog/'.$this->lotId.'/');

        if ($this->validate()) { 
            foreach ($this->images as $id => $image) {
                $pathImageMin = Yii::getAlias('@frontendWeb').'/img/lots/zalog/'.$this->lotId.'/'.$id.'-min-image.'.$image->getExtension();
                $pathImageMax = Yii::getAlias('@frontendWeb').'/img/lots/zalog/'.$this->lotId.'/'.$id.'-max-image.'.$image->getExtension();

                $image->saveAs( $pathImageMax );
                Image::thumbnail($pathImageMax, 1000, 1000)->save(Yii::getAlias($pathImageMin), ['quality' => 80]);

                $images[$id]['min'] = '/img/lots/zalog/'.$this->lotId.'/'.$id.'-min-image.'.$image->getExtension();
                $images[$id]['max'] = '/img/lots/zalog/'.$this->lotId.'/'.$id.'-max-image.'.$image->getExtension();
                
            }

            $lot = LotsZalogUpdate::findOne((int)$this->lotId);

            $lot->images = $images;

            return ['status' => $lot->update(), 'src' => $images];
        } else {
            var_dump($this->errors);
            return ['status' => false];
        }

    }
}