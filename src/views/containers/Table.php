<?php
require_once "./src/views/containers/ContainerElement.php";
require_once "./src/tools/utils/HtmlUtils.php";

class Table extends ContainerElement
{
    public function __construct(?string $class = null)
    {
        parent::__construct('<table' . HtmlUtils::addClassAttr($class) . '>', '</table>');
    }
}