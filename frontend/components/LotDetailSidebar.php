<?php
namespace frontend\components;

use yii\base\Widget;

class LotDetailSidebar extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('lotDetailSidebar');
    }
}