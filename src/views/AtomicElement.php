<?php
require_once "./src/tools/interfaces/iElement.php";
class AtomicElement implements iElement{
    // properties
    private string $html;

    public function __construct(string $html){
        $this->html = $html;
    }

    public function show(): string {
        return $this->html;
    }
}