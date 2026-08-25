<?php
namespace Wiki\views\fields;
use Wiki\tools\interfaces\iElement;
class HiddenField implements iElement {

    protected string $html;

    public function __construct(string $name, string $value){
        $this->html = '<input type="hidden" name="'.$name.'" value="'.$value.'">' . PHP_EOL;
    }

    public function show(): string {
        return $this->html;
    }

}