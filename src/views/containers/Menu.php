<?php
namespace Wiki\views\containers;

use Wiki\dataObjects\ElementInfo;
use Wiki\tools\utils\HtmlUtils;

/**
 * Start of a menu element
 */
class Menu extends ContainerElement
{
    public function __construct(string $class = "nav")
    {
        $element_info = ["html_tag" => "ul", "html_class" => "nav"];
        parent::__construct(new ElementInfo($element_info));
    }
}
