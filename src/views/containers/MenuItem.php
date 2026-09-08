<?php

namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;

/**
 * Draws one menu item
 * @var string $label       Adds the menu display tex
 * @var string $href        Add href tag
 * @var string $class       Add css styling for item
 * @var array $attrs        Add attributes from array
 * @var string $li_class    Add css styling for the list element
 */
class Menuitem extends ContainerElement
{
    public function __construct(string $label, string $href, string $class = '', array $attrs = [], string $li_class = '')
    {
        $safe_label = htmlspecialchars($label);
        $safe_href = htmlspecialchars($href);

        parent::__construct(
            '<li' . HtmlUtils::addClassAttr($li_class) . '>' .
                '<a href="?page=' . $safe_href . '"' . HtmlUtils::addClassAttr($class) . HtmlUtils::addAttrs($attrs) . '>' .
                $safe_label . '</a>',
            "</li>"
        );
    }
}
