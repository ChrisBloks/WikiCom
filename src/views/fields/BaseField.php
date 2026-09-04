<?php

namespace Wiki\views\fields;

use Wiki\tools\interfaces\iElement, Wiki\tools\utils\HtmlUtils;

/**
 * Basic field type that can be added to a form. Should be extended by child classes.
 * @var int $instance_count Static counter keeping track of how many BaseFiel elements have been created.
 * @var string $html    label HTML element for this object, will be equal to: '<label for="{$id}">{$label}</label><br>';
 * @var string $name    value for the HTML name attribute
 * @var string $id      value for the HTML id attribute
 * @var string $label   used to create $html
 * @var string $class   value for the HTML class attribute
 * @var mixed $value    value for the HTML value attribute
 */
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

    /**
     * Updates this object's 'value' property
     * @param mixed $value
     * @return void
     */
    public function setValue(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * Returns this object's HTML. Should be implemented by child classes.
     * @return string
     */
    abstract public function show(): string;

    /**
     * Returns this objects' attribute list to put in its HTML opening tag. 
     * If [x] is this function's output. Then the HTML may look like: <div [x]></div>
     * @param bool $is_array Encodes if the $value parameter is an array.
     * @param string $id value for the HTML id attribute
     * @param string $value value for the HTML value attribute
     * @return string string attributes
     */
    protected function baseAttribs(bool $is_array = false, string $id = "", ?string $value = NULL): string
    {
        return ' name="' . $this->name . ($is_array ? (isset($value) ? "[{$value}]": "[]") : "") . '" id="' . $this->id . ($is_array ? $id : "") . '" class="' . $this->class . '" ';
    }
}
