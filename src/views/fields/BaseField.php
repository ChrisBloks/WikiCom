<?php

namespace Wiki\views\fields;

use Wiki\tools\interfaces\iElement, Wiki\tools\utils\HtmlUtils;

abstract class BaseField implements iElement
{
    static int $instance_count = 0;

    protected string $html;
    protected string $name;
    protected string $id;
    protected string $label;
    protected string $class;
    protected mixed $value;

    public function __construct(string $name, string $label, string $class)
    {
        self::$instance_count++;
        $this->value = "";
        $this->name = $name;
        $this->id = $name . "-" . self::$instance_count;
        $this->label = $label;
        $this->class = $class;
        $this->html = '';
        if (($label === "")) {
            throw new \BadFunctionCallException("Label not set!");
        }
        $this->html .= HtmlUtils::printLabel($this->id, $label);
    }

    public function setValue(mixed $value)
    {
        $this->value = $value;
    }

    abstract public function show(): string;

    protected function baseAttribs(bool $is_array = false, string $id = "",string $value = NULL): string
    {
        return ' name="' . $this->name . ($is_array ? (isset($value) ?   "[{$value}]": "[]") : "") . '" id="' . $this->id . ($is_array ? $id : "") . '" class="' . $this->class . '" ';
    }
}
