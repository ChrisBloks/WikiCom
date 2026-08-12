<?php
require_once "./src/tools/traits/tElementContainer.php";

class ContainerElement implements iElement{


    use tElementContainer;

    // properties
    protected string $html_before;
    protected string $html_after;

    public function __construct(string $html_before, string $html_after){
        $this->html_before = $html_before;
        $this->html_after = $html_after;
    }

    public function show(): string{
        $str = "";
        $str .= $this->html_before;
        $str .= $this->showChildElements();
        $str .= $this->html_after;

        return $str;

    }
}
