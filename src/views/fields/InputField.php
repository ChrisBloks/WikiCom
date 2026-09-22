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
            class: $element_info['field_info']['html_class'] ?? ""
        );
        $this->type = $element_info['field_info']['type'] ?? throw new InvalidArgumentException("InputField did not receive a type!");
        $this->text = $element_info['field_info']['text'] ?? "";
        $this->value = $element_info['field_info']['value'] ?? "";
    }

    public function show(): string
    {
        return HtmlUtils::printLabel($this->id, $this->label)
            . '<input type="' . $this->type . '"' . 
                    'name="' . $this->name . '"' .
                    'id="' . $this->id . '"' .
                    (empty($this->value) ? "" : 'value="' . $this->value . '"') . 
                    'class="' . $this->class . '" ><br>';

                        
    }
}
