<?php
// creates body text
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;

class AuthorText extends WrappedText
{
    public function __construct(string $text, string $class = '')
    {
        parent::__construct($text, 'h3' . HtmlUtils::addClassAttr($class));
    }
}
