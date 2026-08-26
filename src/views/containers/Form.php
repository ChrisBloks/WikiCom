<?php

namespace Wiki\views\containers;

use Wiki\tools\utils, Wiki\views\fields;

class Form extends ContainerElement
{

    // Form class is a child of Container element, to display forms very well


    // properties
    protected array $hiddenfields;

    public function __construct(string $action, string $method, string $submit_caption, string $class = "",string $enctype = "")
    {
        $this->html_before = '<form action="' . $action . '" method="' . $method . '" ' . utils\HtmlUtils::addClassAttr($class) . 'enctype="'.$enctype.'">';

        $this->html_after = '<button type="submit" value="submit">' . $submit_caption . ' </button></form>';
    }

    public function addHiddenField(string $name, string $value): void
    {
        $this->addElement(new fields\HiddenField($name, $value));
    }
}
