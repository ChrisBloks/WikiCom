<?php

namespace Wiki\dataObjects;

use Wiki\dataObjects\ElementInfo,
InvalidArgumentException;


class FieldInfo extends ElementInfo
{
    static protected array $allowed_keys =
        [
            'type',
            'class',
            'name',
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