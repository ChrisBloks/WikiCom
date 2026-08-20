<?php
// Creates header part of main page
require_once "./src/views/containers/WrappedText.php";

class Title extends WrappedText{
    public function __construct(string $text, string $class =''){
        parent::__construct($text, "h1". htmlutils::addClassAttr($class));
    }
}
