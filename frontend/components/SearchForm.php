<?php
namespace frontend\components;

use Yii;
use yii\base\Widget;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;
use frontend\models\SearchLot;
use frontend\models\zalog\FilterLots;

class SearchForm extends Widget
{
    public $type;
    public $lotType = 'all';

    public function run(){

        switch ($this->lotType) {
            case 'zalog':
                    $model = new FilterLots();
            
                    $model->load(Yii::$app->request->get());

                    return $this->render('zalogSearch', compact('model'));
                break;
            default:
                    $model = new SearchLot();

                    $type = $model->type = $this->type;
            
                    return $this->render('smallSearch', compact('model', 'type'));
                break;
        }
        
    }
}