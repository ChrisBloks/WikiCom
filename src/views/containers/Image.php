<?php
// creates body text
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;
class Image extends WrappedText{
    public function __construct(string $name, string $class ='', ){
        parent::__construct('', 'img src='.$name.''. HtmlUtils::addClassAttr($class));
    }
}
