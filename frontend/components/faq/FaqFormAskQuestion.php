<?php
namespace frontend\components\faq;

use yii\base\Widget;

class FaqFormAskQuestion extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('faqFormAskQuestion');
    }
}