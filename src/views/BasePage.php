<?php
require_once "./src/views/HtmlDoc.php";

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

    public function addToHeadContent(iElement $element): void{
        $this->head_container->addElement($element);
    }

    public function addToBodyContent(iElement $element): void{
        $this->body_container->addElement($element);
    }


}