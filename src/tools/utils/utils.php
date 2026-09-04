<?php

namespace Wiki\tools\utils;

class Utils
{
    /**
     * Extract the specified key from either GET or POST and sanitize before returning
     */
    public static function getRequestVar(string $key, bool $frompost, $default = '', bool $asnumber = false): null|string
    {
        if ($asnumber) {
            $result = filter_input(
                $frompost ? INPUT_POST : INPUT_GET,
                $key,
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );
            return ($result === false || $result === null) ? $default : $result;
        }

        $raw = filter_input($frompost ? INPUT_POST : INPUT_GET, $key, FILTER_UNSAFE_RAW);

        if ($raw === false || $raw === null) {
            return $default;
        }
        return htmlspecialchars(strip_tags(trim($raw)), ENT_QUOTES, 'UTF-8');
    }

    public static function getRequestNumber(string $key, bool $frompost, $default = ''): string
    {
        $source = $frompost ? $_POST : $_GET;

        if (!array_key_exists($key, $source) || !is_scalar($source[$key])) {
            return $default;
        }

        $raw = trim((string) $source[$key]);

        if ($raw === '' || !is_numeric($raw)) {
            return $default;
        }

        return (string) $raw;
    }

    public static function getValueFromArray(string $key, array $arr, mixed $default = ''): mixed
    {
        return (isset($arr[$key])
            ? $arr[$key]
            : $default);
    }

    public static function getSesVar(string $key, mixed $default = ""): mixed
    {
        return self::getValueFromArray($key, $_SESSION, $default);
    }
}
