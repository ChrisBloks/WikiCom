<?php
require_once "./src/views/containers/ContainerElement.php";

class MenuItem extends ContainerElement
{
    public function __construct(string $label, string $href)
    {
        $safe_label = htmlspecialchars($label); //is sanitize-achtige functies nodig bij elke info van db?
        $safe_href  = htmlspecialchars($href);

        parent::__construct(
            '<li><a href="?page='. $safe_href. '">'. $safe_label. '</a>',
            "</li>"
        );
    }
}