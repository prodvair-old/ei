<?php
namespace frontend\modules\profile\components;

use yii\base\Widget;

class ProfileMenu extends Widget
{
    public $page;

    public function run(){
        return $this->render('profileMenu', ['page' => $this->page]);
    }
}