<?php
namespace Wiki\views\containers;

use Wiki\tools\interfaces, Wiki\tools\utils;

class AtomicElement implements interfaces\iElement{
    // properties
    private string $html;
    private string $class;

    public function __construct(string $html, string $class =''){
        $this->html = $html;
        $this->class = $class;
    }

    public function show(): string {
        return $this->html. utils\HtmlUtils::addClassAttr($this->class);
    }
}