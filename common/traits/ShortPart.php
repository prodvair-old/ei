<?php

namespace common\traits;

trait ShortPart
{
    /**
     * Short part of a string.
     * @param integer $length of the string from first symbol
     * @param string $name of a field
     * @return string
     */
    public function getShortPart($length = 25, $name = 'name')
    {
        $str = $this->$name;
        mb_internal_encoding("UTF-8");
        return mb_strlen($str) > $length
            ? mb_substr($str, 0, $length) . '...'
            : $str;
    }
}
