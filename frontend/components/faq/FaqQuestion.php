<?php
namespace frontend\components\faq;

use yii\base\Widget;

class FaqQuestion extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('faqQuestion');
    }
}