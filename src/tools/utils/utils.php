<?php
class Utils{

public static function getRequestVar(string $key, bool $frompost, $default = '', bool $asnumber = false)
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
}