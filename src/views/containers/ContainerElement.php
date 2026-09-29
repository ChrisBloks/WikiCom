<?php

namespace Wiki\views\containers;

use Wiki\tools\interfaces\iElement,
    Wiki\tools\traits\tElementContainer;
use ArrayAccess;
use Wiki\controllers\factories\ElementFactory;
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

        // Create opening tag
        if (isset($element_info['html_tag'])) {
            $this->html_before = "<{$element_info['html_tag']} ";

            // Add potential HTML attributes
            foreach ($element_info->getHTMLattributes() as $attr) {
                $this->html_before .=  ($element_info[$attr] ? $attr . '="' . $element_info[$attr] . '" ' : "");
            }

            // Check if optional attributes have been added
            if (isset($element_info['aria_attributes'])) {
                // ASSUMPTION: Each option array is of the form [id => (attribute_name, attribute_value)]
                foreach ($element_info['aria_attributes'] as $attribute) {
                    $this->html_before .= $attribute['name'] . '="' . $attribute['value'] . '" ';
                }
            }

            $this->html_before .= ">" . ($element_info['text'] ?? "");

            // Add closing tag
            $this->html_after = ($element_info['closing_tag'] !== false ? "</{$element_info['html_tag']}>" : "");
        }

        // just print the given text
        else {
            $this->html_before = ($element_info['text'] ?? "");
        }

        // Add potential subfields
        if (isset($element_info['sub_fields'])) {
            foreach($element_info['sub_fields'] as $sub_element_info){
                $this->addElement(ElementFactory::createElement($sub_element_info));
            }
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

    public function __toString(): string
    {
        return htmlentities($this->html_before) .
            htmlentities($this->showChildElements()) .
            htmlentities($this->html_after);
    }
}
