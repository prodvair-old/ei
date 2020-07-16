<?php

namespace backend\modules\admin\traits;

trait TrExtractor
{
    /**
     * Get <tr> tags from a table.
     * @return string
     */
    public function getTr($html)
    {
        return preg_match('/<tbody>(.*?)<\/tbody>/sm', $html, $matches)
            ? $matches[1]
            : $html;
    }
}
