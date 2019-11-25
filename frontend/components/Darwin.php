<?php
namespace frontend\components;

use Yii;
use yii\base\Widget;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;
use frontend\models\SearchLot;

namespace frontend\components;

use yii\base\Widget;

class Darwin extends Widget
{
   

    public function run(){

        return $this->render('darwin');
    }
}