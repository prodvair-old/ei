<?php

namespace backend\modules\admin\traits;

trait TrExtractor
{
    /**
     * Извлечь строки из html таблицы.
     */
    public function getTr($html)
    {
        return preg_match('/<tbody>(.*?)<\/tbody>/sm', $html, $matches)
            ? $matches[1]
            : $html;
    }
}
