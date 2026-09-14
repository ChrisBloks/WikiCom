<?php

namespace Wiki\views\fields;

use InvalidArgumentException;
use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;


class Checkbox extends BaseField
{
    static protected array $required_attributes = ['name', 'label', 'class', 'value'];
    protected bool $checked;

    public function __construct(array $field_info)
    {
        // Check if all required attributes have been given
        foreach($this->required_attributes as $attr){
            try{
                $field_info[$attr];
            } 
            catch (\Throwable $e) {
                echo "Warning: tried to create Checkbox without a {$attr}. {$e->getMessage()}";
            }
        }
        
        // Set properties
        parent::__construct(
            name: $field_info['name'], 
            label: $field_info['label'],
            class: $field_info['class'],
            value: $field_info['value'], // For a checkbox, its value should be equal to its id.
        );
        $this->checked = ($field_info['checked'] ?? false);
    }


    public function show(): string
    {
        $html = '<input type="checkbox"' . $this->baseAttribs() .
                ($this->value ? " value='{$this->value }' " : '') .
                ($this->checked ? ' checked ' : '') . '>' .
                HtmlUtils::printLabel($this->id, $this->label) . "";

        return $html;
    }
}
