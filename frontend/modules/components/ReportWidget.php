<?php
namespace frontend\modules\components;

use yii\base\Widget;

class ReportWidget extends Widget
{
    public $reports;
    public $lot;

    public function run(){
        return $this->render('reportBlock', ['reports' => $this->reports, 'lot' => $this->lot]);
    }
}