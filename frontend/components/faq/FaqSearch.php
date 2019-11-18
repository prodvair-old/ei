<?php
namespace frontend\components\faq;

use yii\base\Widget;

class FaqSearch extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('faqSearch');
    }
}