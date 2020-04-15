<?php

namespace common\models\db;

/**
 * Интерфейс профайла
 */
interface ProfileInterface
{
    /**
     * Полное имя или название организации
     * @retutn string
     */
    public function getFullName();
}
