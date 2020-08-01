<?php
namespace frontend\modules\components;

use frontend\modules\forms\ReportForm;
use yii\base\Widget;

class PurchasedReportWidget extends Widget
{
    public $reports;

    public function run(){
        return $this->render('purchasedReportBlock', ['reports' => $this->reports]);
    }
}