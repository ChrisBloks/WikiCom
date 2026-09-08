<?php
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;

/**
 * Start of a menu element
 */
class Menu extends ContainerElement
{
    public function __construct(string $class = "nav")
    {
        parent::__construct('<ul' . HtmlUtils::addClassAttr($class) . '>', '</ul>');
    }
}
