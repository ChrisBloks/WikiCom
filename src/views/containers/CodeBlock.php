<?php
// creates body text
require_once "./src/views/containers/WrappedText.php";

class CodeBlock extends WrappedText{
    public function __construct(string $text, string $class =''){
        parent::__construct($text, 'code'. HtmlUtils::addClassAttr($class));
    }

    public function show(): string
    {
        $inner = parent::show(); 
        $pre = new WrappedText($inner, 'pre');
        return $pre->show();
    }
}
