<?php

namespace Wiki\views\fields;

use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;


class CheckBoxGroup extends BaseField
{

    protected array $options = [];

    public function __construct(string $name, string $label, string $class, array $options, array $value)
    {
        parent::__construct($name, $label, $class);
        $this->options = $options;
        $this->value = $value;
    }


    public function show(): string
    {
    
        $ret = '<div class="fw-bold mb-1">' . HtmlUtils::printLabel($this->id, $this->label) . '</div>';
        $ret .= '<div class="checkbox_group">';
        $ret .= '<div style="column-count: 3;">'; // only the checkboxes go in the column container
        foreach ($this->options as $value => $display) {
            $checked = isset($this->value[$value]) ? $this->value[$value]:'';
            $ret .= '<input type="checkbox"' . $this->baseAttribs(true, $value) . 'value="' . $value . '"' . ($checked ? 'checked' : '') . '>';
            $ret .= HtmlUtils::printLabel($this->id, $display) . "";
            $ret .= '</div>';
        }
        $ret .= '</div>';

        return $ret .= "</div>";
    }
}
