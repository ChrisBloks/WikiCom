<?php
require_once "./src/views/containers/WrappedText.php";

class Footer extends WrappedText{

public function __construct(string $text, string $class = ''){
    parent::__construct($text, 'footer'. HtmlUtils::addClassAttr($class) );
}
}