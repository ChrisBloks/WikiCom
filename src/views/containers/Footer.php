<?php

namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;

class Footer extends WrappedText
{

    public function __construct(string $text, string $class = '')
    {
        parent::__construct($text, 'footer' . HtmlUtils::addClassAttr($class));
    }
}
