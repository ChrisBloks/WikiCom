<?php
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;

/**
 * Type of wrapped text specifically for a page title
 * @var $text to display html text
 * @var $class addition of class
 */
class Title extends WrappedText
{
    public function __construct(string $text, string $class = '')
    {
        parent::__construct($text, "h1" . htmlutils::addClassAttr($class));
    }
}
