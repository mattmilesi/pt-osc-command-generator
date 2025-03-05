<?php

namespace PtOscCommandGenerator;

use ReflectionClass;

abstract class DsnOption
{
    public const CHARSET = 'A';
    public const DATABASE = 'D';
    public const MYSQL_READ_DEFAULT_FILE = 'F';
    public const HOST = 'h';
    public const PASSWORD = 'p';
    public const PORT = 'P';
    public const MYSQL_SOCKET = 'S';
    public const TABLE = 't';
    public const USER = 'u';
    public const MYSQL_SSL = 's';

    public static function getAll(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    public static function isValid($option): bool
    {
        $constants = static::getAll();
        return in_array($option, $constants);
    }
}