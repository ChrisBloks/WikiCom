<?php

namespace Wiki\views\fields;

use Wiki\tools\utils\HtmlUtils;

class Select extends BaseField
{

    protected array $options = [];
    protected string $selected_option;

    public function __construct(string $name, string $label, string $class, array $options, string $selected_option = "", string $options_class = "")
    {
        parent::__construct($name, $label, $class);
        $this->options = $options;
        $this->selected_option = $selected_option;
    }


    public function show(): string
    {
        $ret = HtmlUtils::printLabel($this->id, $this->label)
            . '<select' . $this->baseAttribs() . ">";

        foreach ($this->options as $value => $display) {
            $ret .= '<option '.
                    (!empty($option_class) ? 'class="'.$option_class.'"' : ""). 
                    'value="' . $value . '">'.
                    ($value == $this->selected_option ? ' selected' : '') .
                    $display.
                '</option>';
        }


        return $ret .= "</select>";
    }
}
