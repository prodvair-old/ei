<?php
namespace frontend\components;

use Yii;
use yii\base\Widget;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;
use frontend\models\SearchLot;

class SearchForm extends Widget
{
    public $type;

    public function run(){

        $model = new SearchLot();

        $type = $model->type = $this->type;

        return $this->render('smallSearch', compact('model', 'type'));

        
    }
}