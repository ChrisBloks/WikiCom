<?php

namespace Wiki\dataObjects;

use Override;
use Wiki\dataObjects\ElementInfo,
    InvalidArgumentException;

class LinkedElementInfo extends ElementInfo
{    
    static protected array $allowed_keys =
        [
            'html_tag',
            'html_class',
            'php_class',
            'page_value',
            'role',
            'data-bs-toggle',
            'aria-expanded',
            'label',
            'field_info',
            'data-user-id',
            'data-target-page'
        ];

    #[Override]
    public function getHTMLAttributes(): array
    {
        return ['class'=>'class', 'page_value'=>'href', 'role'=>'role', 'data-bs-toggle'=>'data-bs-toggle', 'aria-expanded' =>'aria-expanded','data-user-id' => 'data-user-id', 'data-target-page' => 'data-target-page'];
    }
}