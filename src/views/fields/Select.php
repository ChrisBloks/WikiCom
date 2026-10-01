<?php

namespace Wiki\views\fields;

use Wiki\tools\utils\HtmlUtils;

class Select extends BaseField
{

    protected array $options = [];
    protected string $selected_option;
    protected string $option_class;

    public function __construct(string $name, string $label, string $class, array $options, string $selected_option = "", string $option_class = "")
    {
        parent::__construct($name, $label, $class);
        $this->options = $options;
        $this->selected_option = $selected_option;
        $this->option_class = $option_class;
    }


    public function show(): string
    {
        $ret = HtmlUtils::printLabel($this->id, $this->label)
            . '<select' . $this->baseAttribs() . ">";

        foreach ($this->options as $key => $value) {
            $ret .= '<option '.
                    (!empty($this->option_class) ? 'class="'.$this->option_class.'"' : ""). 
                    'value="' . $key . '"'
                    .($key == $this->selected_option ? ' selected' : '') . ">"
                    .$value.
                '</option>';
        }

        
        return $ret .= "</select>";
    }
}
