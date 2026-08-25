<?php
// Creates header part of main page
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;

class Header extends WrappedText{
    public function __construct($text, string $class){
        parent::__construct($text, 'header'. HtmlUtils::addClassAttr($class));
    }
}
