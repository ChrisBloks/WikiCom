<?php

namespace Wiki\views\fields;

use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;

class CheckBoxGroup extends BaseField
{

    protected array $options = [];

    public function __construct(string $name, string $label, string $class, array $options)
    {
        parent::__construct($name, $label, $class);
        $this->options = $options;
        $this->value = [];
    }


    public function show(): string
    {

        $ret = '<div class="checkbox_group">';
        $ret .= HtmlUtils::printLabel($this->id, $this->label) . "<br>";

        foreach ($this->options as $value => $display) {
            $checked = in_array($value, $this->value);
            $ret .= '<input type="checkbox"' . $this->baseAttribs(true, $value) . 'value="' . $display . '"' . ($checked ? 'checked' : '') . '>';
            $ret .= HtmlUtils::printLabel($this->id, $display) . "<br>";
        }

        return $ret .= "</div>";
    }
}
