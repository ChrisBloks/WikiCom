<?php

namespace Wiki\views\fields;

use Wiki\tools\utils\HtmlUtils;

class Select extends BaseField
{

    protected array $options = [];
    protected string $text = '';

    public function __construct(string $name, string $label, string $class, array $options, string $text)
    {
        parent::__construct($name, $label, $class);
        $this->options = $options;
        $this->text = $text;
    }


    public function show(): string
    {
        $ret = HtmlUtils::printLabel($this->id, $this->label)
            . '<select' . $this->baseAttribs() . ">";

        foreach ($this->options as $value => $display) {
            $selected = '';
            if ($this->text == $value) {
                $selected = 'selected';
            }

            $ret .= '<option value="' . $value . '" ' . ($selected ? ' selected' : '') . '>' . $display . '</option>';
        }


        return $ret .= "</select>";
    }
}
