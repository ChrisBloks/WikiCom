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
            'class',
            'href',
            'role',
            'data-bs-toggle',
            'aria-expanded',
            'label'
        ];

    #[Override]
    public function getHTMLAttributes(): array
    {
        return ['class', 'href', 'role', 'data-bs-toggle', 'aria-expanded'];
    }
}