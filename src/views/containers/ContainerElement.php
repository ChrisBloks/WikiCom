<?php

namespace Wiki\views\containers;

use Wiki\tools\interfaces\iElement,
Wiki\tools\traits\tElementContainer;
use ArrayAccess;
use Wiki\tools\utils\HtmlUtils;


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

    public function __construct(ArrayAccess $element_info)
    {
        if ($element_info->isEmpty()) {
            $this->html_before = "";
            $this->html_after = "";
            return;
        }
        $this->html_before = "<" . $element_info['html_tag'] . "" . HtmlUtils::addClassAttr($element_info['html_class']) . ">";
        $this->html_after = "</" . $element_info['html_tag'] . ">";
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
