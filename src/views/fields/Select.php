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

        foreach ($this->options as $select_info) {
            $ret .= '<option '.
                    (!empty($option_class) ? 'class="'.$option_class.'"' : ""). //
                    'value="' . $select_info['value'] . '"'
                    .($select_info['value'] == $this->selected_option ? ' selected' : '') . ">"
                    .$select_info['label'].
                '</option>';
        }


        return $ret .= "</select>";
    }
}
