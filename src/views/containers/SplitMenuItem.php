<?php
require_once "./src/views/containers/ContainerElement.php";

class SplitMenuItem extends ContainerElement
{
    public function __construct(string $label, string $href, string $link_class = '', string $li_class = '')
    {
        $safe_label = htmlspecialchars($label);
        $safe_href  = htmlspecialchars($href);

        $main_link = '<a href="?page=' . $safe_href . '"' . HtmlUtils::addClassAttr($link_class) . '>' . $safe_label . '</a>';

        $toggle = '<a href="#"' . HtmlUtils::addClassAttr($link_class . ' dropdown-toggle dropdown-toggle-split') .
            HtmlUtils::addAttrs([
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'aria-expanded' => 'false',
            ]) . '><span class="visually-hidden">Toggle dropdown</span></a>';

        parent::__construct(
            '<li' . HtmlUtils::addClassAttr($li_class . ' dropdown d-flex align-items-center') . '>' . $main_link . $toggle,
            '</li>'
        );
    }
}