<?php
require_once "./src/views/containers/WrappedText.php";

class Footer extends WrappedText{

public function __construct(string $text){
    parent::__construct($text, 'footer'. HtmlUtils::addClassAttr('border-top ms-3 bg-secondary-subtle') );
}
}