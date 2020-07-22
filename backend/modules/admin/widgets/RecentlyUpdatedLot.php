<?php

namespace backend\modules\admin\widgets;

use yii\base\Widget;
use common\models\db\Lot;

/**
 * Recenttly updated lots widget.
 */
class RecentlyUpdatedLot extends Widget
{
    /* @var integer $max lot's models */
    public $max = 5;
    
    private $models;

    public function init()
    {
        parent::init();
        $this->models = Lot::getRecentlyUpdated($this->max);
    }

    public function run()
    {
        return $this->render('recently_updated_lot', [
            'models' => $this->models,
        ]);
    }
}
