<?php

namespace Wiki\views\fields;

use Wiki\tools\utils\HtmlUtils;

class Select extends BaseField
{

    protected array $options = [];

    public function __construct(string $name, string $label, string $class, array $options, string $option_class = "")
    {
        parent::__construct($name, $label, $class);
        $this->options = $options;
    }


    public function show(): string
    {
        $ret = HtmlUtils::printLabel($this->id, $this->label)
            . '<select' . $this->baseAttribs() . ">";

        foreach ($this->options as $value => $display) {
            $ret .= '<option '.
                    (!empty($option_class) ? 'class="'.$option_class.'"' : ""). 
                    'value="' . $value . '">'.
                    $display.
                '</option>';
        }

        return $ret .= "</select>";
    }
}
