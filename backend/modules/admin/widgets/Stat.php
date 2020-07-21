<?php

namespace backend\modules\admin\widgets;

use yii\base\Widget;

/**
 * Stat data widget.
 */
class Stat extends Widget
{
    /* @var string $sid string ID of a pool of statistic data */
    public $sid;
    
    /* @var boolean $user_dependent */
    public $user_dependent = false;

    public function run()
    {
        return $this->render('stat_' . $this->sid, [
            $this->sid => \common\models\db\Stat::getDefs($this->sid, $this->user_dependent),
        ]);
    }
}
