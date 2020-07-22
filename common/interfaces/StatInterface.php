<?php

namespace common\interfaces;

/**
 * Interface for Stat jobs.
 */
interface StatInterface
{
    /**
     * Update Stat values.
     * Every variable has to have 'caption' and 'value' and some more.
     * Every value should be updated and returned in the same array.
     * 
     * @param array   $vars
     * @param integer $user_id
     * @return array
     * @see common/migrations/*_stat.php
     */
    public static function updateValues($vars, $user_id = false);
}
