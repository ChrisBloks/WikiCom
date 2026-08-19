<?php
require_once "./src/views/containers/ContainerElement.php";

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