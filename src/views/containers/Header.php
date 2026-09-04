<?php
// Creates header part of main page
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;
/**
 * Type of wrapped text specifically for authors
 * @var $text to display html text
 * @var $class addition of class
 */
class Header extends WrappedText
{
    public function __construct(string $text, string $class)
    {
        parent::__construct($text, 'header' . HtmlUtils::addClassAttr($class));
    }
}
