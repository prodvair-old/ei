<?php

namespace frontend\modules\components;

use common\models\db\Region;
use frontend\modules\models\LotSearch;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class SearchForm extends Widget
{
    public $type;
    public $url;
    public $typeZalog;
    public $lotType = 'all';
    public $btnColor;
    public $color;

    public function run()
    {
        $model = new LotSearch();

        $type = $this->type;
        $model->type = $this->url;

        $btnColor = $this->btnColor;
        $color = $this->color;

        $url = $this->url;

        $regions = Region::find()->select(['id', 'name'])->orderBy(['name' => SORT_ASC])->all();
        $regionList[0] = 'По всей России';
        foreach ($regions as $region) {
            $regionList[$region->id] = $region->name;
        }

        return $this->render('smallSearch', compact('model', 'url', 'regionList', 'type', 'typeZalog', 'btnColor', 'color'));


    }
}
