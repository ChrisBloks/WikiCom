<?php

namespace Wiki\views\containers;

use Wiki\tools\interfaces\iElement,
    Wiki\tools\traits\tElementContainer;
use ArrayAccess;
use Wiki\tools\interfaces\iElementInfo;
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

    public function __construct(iElementInfo $element_info)
    {

        $this->html_before = "";
        $this->html_after = "";

        if (isset($element_info['html_tag'])) {
            $this->html_before = "<{$element_info['html_tag']} ";
            foreach ($element_info->getHTMLattributes() as $attr => $val) {
                $this->html_before .= $attr . '="' . $val . '" ';
            }
            $this->html_before .= ">" . ($element_info['text'] ?? "");
            $this->html_after = ($element_info['closing_tag'] !== false ? "</{$element_info['html_tag']}>" : "");
        }
        
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
