<?php
// creates body text
require_once "./src/views/containers/WrappedText.php";

class AuthorText extends WrappedText{
    public function __construct(string $text, string $class =''){
        parent::__construct($text, 'h3'. HtmlUtils::addClassAttr($class));
    }
}
