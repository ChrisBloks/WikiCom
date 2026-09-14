<?php

namespace Wiki\views\fields;

use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;
use Wiki\views\containers\AtomicElement;


class SearchableCheckboxes extends BaseField
{
    static protected array $required_attributes = ['name', 'label', 'class', 'options'];
    protected array $options = [];
    protected bool $addable_options = false;

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
        $this->addable_options = (isset($field_info['addable_options'])) ? $field_info['addable_options']:false;
    }


    public function show(): string
    {
        // Add container for the search field and checkbox gorup
        $html = "<div class='{$this->class}' " .
                (isset($this->id) ? "id='{$this->id}'" : "") .
                '>' . HtmlUtils::printLabel($this->id, $this->label);

        // Add search field
        $search_field_info = [
            'name' => 'searchField',
            'class' => 'searchField form-control', // search-input',
            'label' => '',
        ];

        $html .= (new SearchField($search_field_info))->show();

        if ($this->addable_options){
            $html .= (new AtomicElement('<div id="add-tag-widget" class="d-flex gap-2 mt-2 mb-2">
            <input type="text" id="new-tag-name"
            class="form-control form-control-sm" placeholder="New tag">
            <button type="button" id="add-tag-btn" 
            class="btn btn-sm btn-secondary">Add tag</button>
            </div>'))->show();
        }

        // Add checkboxgroup
        $html .= '<div class="checkbox_group">';

        foreach ($this->options as $checkbox_info) {
            $html .= '<div class="checkbox_container">';
            $html .= (new Checkbox($checkbox_info))->show();
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html .= "</div>";
    }
}
