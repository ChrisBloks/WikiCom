<?php
require_once "./src/views/fields/HiddenField.php";
require_once "./src/views/containers/ContainerElement.php";
class Form extends ContainerElement{

    // Form class is a child of Container element, to display forms very well


    // properties
    protected array $hiddenfields;

    public function __construct(string $action, string $method, string $submit_caption, string $class =""){
        $this->html_before = '<form action="' . $action . '" method="' . $method . '" '.HtmlUtils::addClassAttr($class).'>';
    
        $this->html_after = '<button type="submit" value="submit">'. $submit_caption . ' </button></form>';
    }

    public function addHiddenField(string $name, string $value): void{
        $this->addElement(new HiddenField($name, $value));
    }

}

