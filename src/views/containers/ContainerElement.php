<?php

namespace Wiki\views\containers;

use Wiki\tools\interfaces\iElement,
     Wiki\tools\traits\tElementContainer;


/**
 * ContainerElement contains html elements and shows the child elements in a wrapper
 * @var $html_before opening html 
 * @var $html_after closing html 
 * @uses tElementContainer
 */
class ContainerElement implements iElement
{
    use tElementContainer;

    // properties
    protected string $html_before;
    protected string $html_after;

    public function __construct(string $html_before, string $html_after)
    {
        $this->html_before = $html_before;
        $this->html_after = $html_after;
    }

    /**
     * Loops through child elements
     * @return string
     */
    public function show(): string
    {
        $str = "";
        $str .= $this->html_before;
        $str .= $this->showChildElements();
        $str .= $this->html_after;

        return $str;
    }
}
