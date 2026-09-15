<?php

namespace Wiki\views\containers;

use Wiki\tools\interfaces, Wiki\tools\utils;
use ArrayAccess,
Wiki\tools\utils\HtmlUtils;

/**
 * Used to create html code
 * @var $html contains the html code
 * @var $class used to add class attributes
 */
class AtomicElement implements interfaces\iElement
{
    // properties
    protected string $html_before;
    protected string $html_after;
    protected string $html;

    public function __construct(ArrayAccess $element_info)
    {
        $this->html_before = (isset($element_info['html_tag']) ? "<" . $element_info['html_tag'] . "" . HtmlUtils::addClassAttr($element_info['html_class']) . ">" : "");
        $this->html_after = (isset($element_info['html_tag']) ? "</" . $element_info['html_tag'] . ">" : "");

        $this->html = $this->html_before .$element_info['text'].$this->html_after;
    }


    public function show(): string
    {
        return $this->html;
    }
}
