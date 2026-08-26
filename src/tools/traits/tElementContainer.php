<?php

namespace Wiki\tools\traits;

use Wiki\tools\interfaces;

trait tElementContainer
{

    private array $collection = [];

    public function addElement(interfaces\iElement $element): void
    {
        $this->collection[] = $element;
    }

    public function showChildElements(): string
    {
        $str = "";
        foreach ($this->collection as $element) {
            $str .= $element->show();
        }
        return $str;
    }
}
