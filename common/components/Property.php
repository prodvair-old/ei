<?php

namespace common\components;

/**
 * Константы, определяющие перечислимые свойства.
 * ```php
 * sergmoro1\lookup\Lookup::items(Property::USER_ROLE, true)
 * sergmoro1\lookup\Lookup::item(Property::USER_ROLE, $data->role, true)
 * ```
 */
class Property {
    const USER_ROLE             = 1;
    const USER_STATUS           = 2;
    const GENDER                = 3;
    const PERSON_ACTIVITY       = 4;
    const ORGANIZATION_STATUS   = 5;
    const ORGANIZATION_TYPE     = 6;
    const ORGANIZATION_ACTIVITY = 7;    
    const TORG_PROPERTY         = 8;
    const TORG_OFFER            = 9;
    const SUM_MEASURE           = 10;
    const LOT_STATUS            = 11;
    const LOT_REASON            = 12;
}
