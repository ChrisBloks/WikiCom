<?php
namespace Wiki\views;

use Wiki\views\containers\ContainerElement;
use Wiki\tools\interfaces;

class BasePage extends HtmlDoc{

    //properties
    private ContainerElement $head_container;

    private ContainerElement $body_container;

    public function __construct(){
        $this->head_container = new ContainerElement("", "");
        $this->body_container = new ContainerElement("", "");
    }


    protected function headContent(): void{
        echo $this->head_container->show();
    }

    protected function bodyContent(): void{
        echo $this->body_container->show();
    }

    public function addToHeadContent(interfaces\iElement $element): void{
        $this->head_container->addElement($element);
    }

    public function addToBodyContent(interfaces\iElement $element): void{
        $this->body_container->addElement($element);
    }


}