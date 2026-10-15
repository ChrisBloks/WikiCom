<?php

namespace Wiki\tools\dataObjects;



class FieldInfo extends ElementInfo
{
    static protected array $allowed_keys =
        [
            'type',
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
            'element_order'
        ];

}