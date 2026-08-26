<?php
// creates body text
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;

class BodyText extends WrappedText
{
    public function __construct(string $text, string $class = '')
    {
        parent::__construct($text, 'p' . HtmlUtils::addClassAttr($class));
    }
}
