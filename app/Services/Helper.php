<?php

namespace App\Services;

class Helper
{
    public static function startsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    public static function contains(string $haystack, string $needle): bool
    {
        return stripos($haystack, $needle) !== false;
    }
}