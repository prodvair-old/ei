<?php

namespace common\widgets;

use yii\base\Widget;

/**
 * Вывод документов, закрепленных за моделью.
 */
class Document extends Widget
{
    public $title;
    public $model;
    
    public function run()
    {
        return $this->render('document', [
            'title' => $this->title,
            'model' => $this->model,
        ]);
    }
}
