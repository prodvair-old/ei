<?php

namespace frontend\modules\components;

use yii\base\Widget;

class LotDetailSidebar extends Widget
{
    public $lot;

    public function run()
    {
        return $this->render('lotDetailSidebar', ['lot'=>$this->lot]);
    }
}