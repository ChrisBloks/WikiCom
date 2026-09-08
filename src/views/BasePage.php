<?php

namespace Wiki\views;

use Wiki\views\containers\ContainerElement;
use Wiki\tools\interfaces\iElement;
/**
 * Allows addition of elements to the head or body section of a HTML page
 * @var ContainerElement $head_container Contains all elements needed in the <head> of a page
 * @var ContainerElement $body_container  Contains all elements needed in the <body> of a page
 */
class BasePage extends HtmlDoc
{

    //properties
    private ContainerElement $head_container;

    private ContainerElement $body_container;

    public function __construct()
    {
        $this->head_container = new ContainerElement("", "");
        $this->body_container = new ContainerElement("", "");
    }

    /**
     * Calls show for all elements in the head section
     * @return void
     */
    protected function headContent(): void
    {
        echo $this->head_container->show();
    }
    /**
     * Calls show for all elements in the body section
     * @return void
     */
    protected function bodyContent(): void
    {
        echo $this->body_container->show();
    }

    /**
     * Adds an element to the head container
     * @param iElement $element Element that you want to add to the head
     * @return void
     */
    public function addToHeadContent(iElement $element): void
    {
        $this->head_container->addElement($element);
    }

    /**
     * Adds an element to the body container
     * @param iElement $element Element that you want to add to the body
     * @return void
     */
    public function addToBodyContent(iElement $element): void
    {
        $this->body_container->addElement($element);
    }
}
