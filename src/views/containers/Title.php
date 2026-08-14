<?php
// Creates header part of main page
require_once "./src/views/containers/WrappedText.php";

class Title extends WrappedText{
    public function __construct($text){
        parent::__construct($text, "h1");
    }
}
