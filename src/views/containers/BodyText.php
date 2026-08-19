<?php
// creates body text
require_once "./src/views/containers/WrappedText.php";

class BodyText extends WrappedText{
    public function __construct(string $text, string $class =''){
        parent::__construct($text, 'p'. HtmlUtils::addClassAttr($class));
    }
}
