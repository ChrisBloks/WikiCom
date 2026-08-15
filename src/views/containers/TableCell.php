<?php
require_once "./src/views/containers/ContainerElement.php";
require_once "./src/tools/utils/HtmlUtils.php";

class TableCell extends ContainerElement
{
    public function __construct(bool $isHeader = false, ?string $class = null)
    {
        $tag = $isHeader ? 'th' : 'td';
        parent::__construct("<$tag" . HtmlUtils::addClassAttr($class) . ">", "</$tag>");
    }
}