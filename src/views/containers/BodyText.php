<?php
// creates body text
require_once "./src/views/containers/WrappedText.php";

class BodyText extends WrappedText{
    public function __construct($text){
        parent::__construct($text, "p");
    }
}
