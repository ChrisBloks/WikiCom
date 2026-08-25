<?php
/* Menu
 *  Marius
 *  Draws menu items
 */
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;


class Menu extends ContainerElement
{
    public function __construct(string $class = "nav")
    {
        parent::__construct('<ul' . HtmlUtils::addClassAttr($class) . '>', '</ul>');
    }
}