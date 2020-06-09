<?php

namespace common\components;

/**
 * Функции для манипуляций с составными частями имени. 
 */
class Name {
    /**
     * Получение строки с полным именем
     * 
     * @param string | null $first_name
     * @param string | null $last_name
     * @return string
     */
    public static function getFull($first_name, $last_name) {
        $empty = !($first_name || $last_name);
        return $empty
            ? $first_name // выдаст не задано 
            : ($first_name ? $first_name . ' ' : '') . $last_name;
    }
}
