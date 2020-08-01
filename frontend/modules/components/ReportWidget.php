<?php
namespace frontend\modules\components;

use frontend\modules\forms\ReportForm;
use yii\base\Widget;

class ReportWidget extends Widget
{
    public $reports;
    public $lot;


    public function run(){
        $reportForm = new ReportForm();
        return $this->render('reportBlock', ['reports' => $this->reports, 'lot' => $this->lot, 'reportForm' => $reportForm]);
    }
}