<?php

namespace Wiki\views\fields;

use InvalidArgumentException;
use Wiki\dataObjects\ElementInfo;
use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;

class InputField extends BaseField
{

    protected string $type;
    protected string $text;
    protected string $name;
    public function __construct(ElementInfo $element_info)
    {
        parent::__construct(
            name: $element_info['field_info']['field_name'] ?? "",
            label: $element_info['field_info']['label'] ?? "",
            class:  $element_info['html_class'] ?? " ",
            id: $element_info['html_id'] ?? $element_info['field_info']['field_name'] . self::$instance_count,
        );
        $this->type = $element_info['field_info']['type'] ?? throw new InvalidArgumentException("InputField did not receive a type!");
        $this->text = $element_info['text'] ?? "";
        $this->value = $element_info['field_info']['value'] ?? "";


    }

    public function show(): string
    {
        return HtmlUtils::printLabel($this->id, $this->label) .
            (($this->type === 'hidden') ? '<div style="display:none">' : "") .
            '<input type="' . $this->type . '"' .
            'name="' . $this->name . '"' .
            'id="' . $this->id . '"' .
            (($this->text === null || $this->text === '') ? "" : 'placeholder="' . $this->text . '"') .
            (($this->value === null || $this->value === '') ? "" : 'value="' . $this->value . '"') .
            'class="' . $this->class . '" ><br>' .
            (($this->type === 'hidden') ? '</div>' : "");
    }
}
