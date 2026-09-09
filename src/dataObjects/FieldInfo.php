<?php

namespace Wiki\dataObjects;

use Wiki\dataObjects\ElementInfo;

class FieldInfo extends ElementInfo
{
    static private array $allowed_keys =
        [
            'php_class',
            'tag',
            'name',
            'html_class',
            'id',
            'value',
            'type',
            'action',
            'method',
            'style',
            'text',
            'label',
        ];
}