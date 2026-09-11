<?php

namespace Wiki\views\fields;

use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;


class CheckBoxGroup extends BaseField
{

    protected array $options = [];

    public function __construct(string $name, string $label, string $class, array $options, mixed $value = [])
    {
        parent::__construct($name, $label, $class);
        $this->options = $options;
        $this->value = $value;
    }


    public function show(): string
    {
        $ret = '<div class="fw-bold mb-1">' . HtmlUtils::printLabel($this->id, $this->label);
        $ret .= '<div class="checkbox_group">';
        foreach ($this->options as $checkbox_id => $checkbox_name) {
            $checked = isset($this->options[$checkbox_id]) ? $this->options[$checkbox_id]:'';
            $ret .= '<input type="checkbox"' . $this->baseAttribs($checkbox_id, $checkbox_name, true) . 'value="' . $checkbox_name . '"' . ($checked? 'checked' : '') . '>';
            $ret .= HtmlUtils::printLabel($this->id, $checkbox_name) . "";
        }

        $ret .= '</div>';
        return $ret .= "</div>";
    }
}
