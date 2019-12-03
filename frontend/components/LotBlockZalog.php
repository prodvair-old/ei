<?php
namespace frontend\components;

use frontend\models\UploadZalogLotImage;
use frontend\models\ZalogLotCategorySet;

use yii\base\Widget;

class LotBlockZalog extends Widget
{
    public $lot;
    public $type = 'grid';

    public function run(){
        
        $model = new UploadZalogLotImage();
        $modelCategory = new ZalogLotCategorySet();

        return $this->render('lotBlcokZalog', ['lot' => $this->lot, 'type' => $this->type, 'model' => $model, 'modelCategory' => $modelCategory]);
    }
}