<?php

namespace common\traits;

use Yii;

trait PersonList
{
    /**
     * Persons list.
     * @param ActiveQuery $query
     * @param $string $search
     * @param integer $selected person ID
     * @param boolean $needuUsername need to use username for searching
     * @return array persons list
     */
    public static function makePersonList($query, $search, $selected, $needUsername = false)
    {
        if ($search)
            $query->andFilterWhere(['or',
                ($needUsername ? ['like', 'username', $search] : []),
                ['like', 'first_name', $search],
                ['like', 'middle_name', $search],
                ['like', 'last_name', $search],
                ['like', 'inn', $search]
            ]);
        else
            $query->limit(10);
        
        $models = $query->asArray()->all();
        
        $a = [];
        $a[] = ['id' => 0, 'text' => Yii::t('app', 'Select')];
        foreach($models as $model) {
            $text = $model['full_name'] . ' ' . $model['inn'];
            $text = trim($text) ? $text : ($needUsername ? $model['username'] : '');
            if ($text)
                $a[] = [
                    'id' => $model['id'], 
                    'text' => $text,
                    'selected' => ($model['id'] == $selected),
                ];
        }
        return $a;
    }
}
