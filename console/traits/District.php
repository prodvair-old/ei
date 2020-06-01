<?php

namespace console\traits;

use yii\console\Exception;

trait District
{
    private $district = [
        'Центральный'       => 1,
        'Северо-Западный'   => 2,
        'Южный'             => 3,
        'Северо-Кавказский' => 4,
        'Приволжский'       => 5,
        'Уральский'         => 6,
        'Сибирский'         => 7,
        'Дальневосточный'   => 8,    
    ];

    /**
     * Сопоставление названию округа числового кода.
     */
    public function districtConvertor($name)
    {
        return $name
            ? (isset($this->district[$name]) ? $this->district[$name] : 0)
            : 0;
    }
}
