<?php
require_once "./src/views/WrappedText.php";

class Footer extends WrappedText{

public function __construct($text){
    parent::__construct($text, "footer");
}
}