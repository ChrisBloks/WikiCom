<?php

namespace Wiki\dataObjects;

use Wiki\dataObjects\ElementInfo,
    InvalidArgumentException;

class FormInfo extends ElementInfo
{    static protected array $allowed_keys =
        [
            'action',
            'method',
            'submit_caption',
            'enctype',
            'submit_class',
            'id',
            'action',
            'method',
            'text',
            'label',
        ];


}