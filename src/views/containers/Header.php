<?php
// Creates header part of main page
require_once "./src/views/containers/WrappedText.php";

class Header extends WrappedText{
    public function __construct($text){
        parent::__construct($text, "header");
    }
}
