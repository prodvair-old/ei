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
    public $url;
    public $typeZalog;
    public $lotType = 'all';
    public $btnColor;
    public $color;

    public function run(){

        switch ($this->lotType) {
            case 'zalog':
                    $model = new FilterLots();
            
                    $model->load(Yii::$app->request->get());

                    $url = $this->url;

                    return $this->render('zalogSearch', compact('model', 'url'));
                break;
            default:
                    $model = new SearchLot();

                    $type = $this->type;
                    $model->type = $url;
                    
                    $typeZalog  = $this->typeZalog;
                    $btnColor   = $this->btnColor;
                    $color      = $this->color;

                    $url = $this->url;
            
                    return $this->render('smallSearch', compact('model', 'url', 'type', 'typeZalog', 'btnColor', 'color'));
                break;
        }
        
    }
}