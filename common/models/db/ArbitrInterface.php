<?php

namespace common\models\db;

/**
 * Интерфейс частных лиц или организаций назначенных арбитражными управляющими
 */
interface ArbitrInterface
{
    /**
     * Адрес
     * @return string
     */
    public function getAddress();

    /**
     * Полное имя или название организации
     */
    public function getFullName();
}
