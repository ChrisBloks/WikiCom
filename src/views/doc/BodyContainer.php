<?php
namespace Wiki\views\doc;

use Wiki\views\elements\containers\ContainerElement;
use Wiki\tools\dataObjects\ElementInfo;
use Wiki\tools\interfaces\iElement;

class BodyContainer
{
    // properties
        private ContainerElement $body_container;

    public function __construct()
    {
        $this->body_container = new ContainerElement(new ElementInfo());
    }
    public function getBodyContent(): string
    {
        return $this->body_container->show();
    }

    public function addToBodyContent(iElement $element)
    {
        return $this->body_container->addElement($element);
    }
}
