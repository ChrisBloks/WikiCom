<?php
// creates body text
require_once "./src/views/containers/WrappedText.php";

class Image extends WrappedText{
    public function __construct(string $name, string $class =''){
        parent::__construct('', 'img src='.$name.''. HtmlUtils::addClassAttr($class));
    }
}
