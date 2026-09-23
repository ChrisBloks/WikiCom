<?php

namespace Wiki\dataObjects;

use Wiki\dataObjects\ElementInfo,
InvalidArgumentException;


class FieldInfo extends ElementInfo
{
    static protected array $allowed_keys =
        [
            'type',
            'html_class',
            'field_name',
            'optional',
            'id',
            'value',
            'type',
            'label',
            'options',
            'marked',
            'text',
            'label',
        ];

}