<?php
namespace frontend\components;

use yii\base\Widget;

class LotBlock extends Widget
{
    public $lot;
    public $type = 'grid';

<<<<<<< HEAD
    public function run(){
        return $this->render('lotBlcok', ['lot' => $this->lot, 'type' => $this->type]);
=======
    public function run()
    {
        return $this->render('lotBlcok', ['lot' => $this->lot]);
>>>>>>> c4eb65e203f2b29dbdb75dfa4cee83289e6b03e8
    }
}