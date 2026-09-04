<?php

namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;
/**
 * Type of wrapped text specifically for footer text
 * @var $text to display html text
 * @var $class addition of class
 */
class Footer extends WrappedText
{

    public function __construct(string $text, string $class = '')
    {
        parent::__construct($text, 'footer' . HtmlUtils::addClassAttr($class));
    }
}
