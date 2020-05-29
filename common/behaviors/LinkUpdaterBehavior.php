<?php

namespace common\behaviors;

use yii\base\Behavior;

/**
 * Класс для обновления связей
 */

class LinkUpdaterBehavior extends Behavior
{
    /** @var $host_name string */
    public $host_name;
    /** @var $link_name string */
    public $link_name;

    /**
     * Update one-to-many links.
     * 
     * @param integer $host_id
     * @param array $old links ID
     * @param array $new links ID
     * @throw InvalidParamException
     */
    public static function updateOneToMany($host_id, $old, $new)
    {
        if (!$this->host_name || !$this->link_name)
            throw new InvalidParamException();
        
        $host_name = $this->host_name;
        $link_name = $this->link_name;

        if (!is_array($old)) $old = [];
        if (!is_array($new)) $new = [];
        // delete links if some of them have been deleted in a form
        foreach(array_diff($old, $new) as $i => $link_id) {
            if($link = self::find()->where([$host_name => $host_id, $link_name => $link_id])->one())
                $link->delete();
        }
        // add links if some of them have been added in a form
        foreach(array_diff($new, $old) as $i => $link_id) {
            $link = new self([$host_name => $host_id, $link_name => $link_id]);
            $link->save();
        }
    }
}
