<?php

namespace Wiki\views\fields;

use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;


class SearchableCheckboxes extends BaseField
{
    static protected array $required_attributes = ['name', 'label', 'class', 'options'];
    protected array $options = [];

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
            value: $field_info['value']
        );

        // Nested array. Each subarray is a field_info array for a checkbox.
        $this->options = $field_info['options'];
    }


    public function show(): string
    {
        // Add container for the search field and checkbox gorup
        $html = '<div class="fw-bold mb-1">' . HtmlUtils::printLabel($this->id, $this->label);

        // Add search field
        

        // Add checkboxgroup
        $html .= '<div class="checkbox_group">';
        foreach ($this->options as $checkbox_info) {
            $html .= (new Checkbox($checkbox_info))->show();
        }
        $html .= '</div>';
        return $html .= "</div>";
    }
}
