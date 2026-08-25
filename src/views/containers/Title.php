<?php
// Creates header part of main page
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils, Wiki\views\containers\WrappedText;


class Title extends WrappedText
{
    public function __construct(string $text, string $class = '')
    {
        parent::__construct($text, "h1" . htmlutils::addClassAttr($class));
    }
}
