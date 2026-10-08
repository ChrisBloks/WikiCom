<?php

namespace Wiki\views;

use Wiki\dataObjects\ElementInfo,
    Wiki\models\ElementModel,
    Wiki\views\containers\ContainerElement,
    Wiki\tools\interfaces\iElement,
    Wiki\views\BodyContainer;

/**
 * Allows addition of elements to the head or body section of a HTML page
 * @var ContainerElement $head_container Contains all elements needed in the <head> of a page
 * @var ContainerElement $body_container  Contains all elements needed in the <body> of a page
 */
class BasePage extends HtmlDoc
{

    //properties
    private ContainerElement $head_container;

    private BodyContainer $body_container;

    public function __construct()
    {
        $this->head_container = new ContainerElement(new ElementInfo());
        $this->body_container = new BodyContainer();
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
        echo $this->body_container->getBodyContent();
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

    public function addToBodyContent(iElement $element)
    {
        return $this->body_container->addToBodyContent($element);
    }

    public function returnHeadContent(){
        return $this->head_container->show();
    }

    public function getBodyContent(){
        return $this->body_container->getBodyContent();
    }

}
