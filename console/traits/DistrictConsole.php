<?php

namespace console\traits;

use yii\console\Exception;

trait DistrictConsole
{
    private static $district = [
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
            ? (isset(self::$district[$name]) ? self::$district[$name] : 0)
            : 0;
    }
}
