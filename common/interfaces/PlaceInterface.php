<?php

namespace common\models\db;

/**
 * Интерфейс адресных данных
 */
interface PlaceInterface
{
    /**
     * Адрес
     * @return string
     */
    public function getAddress();
}
