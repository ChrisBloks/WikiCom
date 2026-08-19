<?php
require_once "./src/tools/interfaces/iElement.php";
class AtomicElement implements iElement{
    // properties
    private string $html;
    private string $class;

    public function __construct(string $html, string $class =''){
        $this->html = $html;
        $this->class = $class;
    }

    public function show(): string {
        return $this->html. HtmlUtils::addClassAttr($this->class);
    }
}