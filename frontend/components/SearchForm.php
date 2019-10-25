<?php
namespace frontend\components;

use Yii;
use yii\base\Widget;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;
use frontend\models\SearchLot;

class SearchForm extends Widget
{
    public $lot;
    public $type = 'full';

    public function run(){

        $model = new SearchLot();
        if ($model->load(Yii::$app->request->post())) {
            var_dump($model->search());
        } else {
            if ($this->type == 'small') {
                return $this->render('smallSearch', compact('model'));    
            } else {
                return $this->render('fullSearch', ['lot' => $this->lot, 'type' => $this->type]);
            }
        }

        
    }
}