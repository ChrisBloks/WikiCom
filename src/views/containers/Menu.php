<?php
/* Menu
 *  Marius
 *  Draws menu items
 */

class Menu extends ContainerElement
{
    public function __construct(string $class = "nav")
    {
        parent::__construct('<ul' . HtmlUtils::addClassAttr($class) . '>', '</ul>');
    }
}