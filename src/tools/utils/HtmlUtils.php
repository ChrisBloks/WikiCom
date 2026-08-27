<?php

namespace Wiki\tools\utils;

class HtmlUtils
{
    // prints label

    public static function printLabel(string $id, string $label)
    {
        return
            '<label for="' . $id . '">' . $label . '</label><br>';
    }

    // function to better display var_dump function
    public static function dump(string $var_name, mixed $var_value, bool $as_code = false): void
    {
        echo '<h3>' . $var_name . '</h3><' . ($as_code ? 'code' : 'pre') . '>';
        is_array($var_value) ? print_r($var_value) : var_dump($var_value);
        echo '</' . ($as_code ? 'code' : 'pre') . '>';
    }

    // static method to add class attributes to html elements
    public static function addClassAttr(?string $class): string
    {
        return $class ? ' class="' . htmlspecialchars($class) . '"' : '';
    }

    // allows to store attributes into array for looping methods needing classes
    public static function addAttrs(array $attrs): string
    {
        $out = '';
        foreach ($attrs as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            $out .= ' ' . htmlspecialchars($name) . '="' . htmlspecialchars((string) $value) . '"';
        }
        return $out;
    }
}
