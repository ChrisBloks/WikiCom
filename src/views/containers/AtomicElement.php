<?php

namespace Wiki\views\containers;

use Override;
use Wiki\tools\interfaces\iElementInfo,
    Wiki\tools\interfaces\iElement;
use Wiki\tools\utils\HtmlUtils;

/**
 * Used to create html code
 * @var $html contains the html code
 * @var $class used to add class attributes
 */
class AtomicElement implements iElement
{
    // properties
    private string $html = "";

    public function __construct(iElementInfo $element_info)
    {
        if (isset($element_info['html_tag'])) {
            $html_before = "<{$element_info['html_tag']} ";
            foreach ($element_info->getHTMLattributes() as $attr) {
                $html_before .=  ($element_info[$attr] ? $attr . '="' . $element_info[$attr] . '" ' : "");
            }
            $html_before .= ">";

            $this->html =
                $html_before .
                ($element_info['label'] ?? "") .
                ($element_info['text'] ?? "") .
                ($element_info['closing_tag'] !== false ? "</{$element_info['html_tag']}>" : "");
        } else {
            $this->html = ($element_info['text'] ?? "");
        }
    }

    public function show(): string
    {
        return $this->html;
    }

    public function __toString(): string {
        return get_class($this) . ": " . htmlentities($this->html);
    }

    #[Override]
    public function addElement(iElement $element): void
    {
        throw new \Exception('Tried to add an Element to a non-container');
    }
}
