<?php

namespace Wiki\views\containers;

use Wiki\tools\interfaces\iElementInfo,
    Wiki\tools\interfaces\iElement;

/**
 * Used to create html code
 * @var $html contains the html code
 * @var $class used to add class attributes
 */
class AtomicElement implements iElement
{
    // properties
    private string $html;

    public function __construct(iElementInfo $element_info)
    {
        if (isset($element_info['html_tag'])) {
            $html_before = "<{$element_info['html_tag']} ";
            foreach ($element_info->getHTMLattributes() as $attr => $val) {
                $html_before .= $attr . '="' . $val . '" ';
            }
            $html_before .= ">";

            $this->html =
                $html_before .
                ($element_info['text'] ?? "") .
                ($element_info['closing_tag'] !== false ? "</{$element_info['html_tag']}>" : "");
        }
    }


    public function show(): string
    {
        return $this->html;
    }
}
