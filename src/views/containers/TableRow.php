<?php
require_once "./src/views/containers/ContainerElement.php";
require_once "./src/tools/utils/HtmlUtils.php";

class TableRow extends ContainerElement
{
    public function __construct(?string $class = null)
    {
        parent::__construct('<tr' . HtmlUtils::addClassAttr($class) . '>', '</tr>');
    }
}