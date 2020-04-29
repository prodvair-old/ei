<?php

namespace common\interfaces;

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
