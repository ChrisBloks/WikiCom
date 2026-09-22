<?php

namespace Wiki\views\fields;

use Wiki\dataObjects\ElementInfo;
use Wiki\dataObjects\FieldInfo;
use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;
use Wiki\views\containers\AtomicElement;


class SearchableCheckboxes extends BaseField
{
    protected array $options = [];
    protected bool $addable_options = false;

    public function __construct(ElementInfo $element_info)
    {

        $field_info = $element_info['field_info'];
        
        // Set properties
        parent::__construct(
            name: $field_info['name'], 
            label: $field_info['label'],
            class: $field_info['class'],
            value: $field_info['value']
        );

        // Nested array. Each subarray is a field_info array for a checkbox.
        $this->options = $element_info['options_info'];
        $this->addable_options = $field_info['addable_options'] ?? false;
    }


    public function show(): string
    {
        // Add container for the search field and checkbox gorup
        $html = "<div class='{$this->class}' " .
                (isset($this->id) ? "id='{$this->id}'" : "") .
                '>' . HtmlUtils::printLabel($this->id, $this->label);

        // Add search field
        $html .= (new SearchField(
                    new ElementInfo([
                        'name' => 'searchField',
                        'class' => 'searchField form-control', // search-input',
                        'label' => '',
                    ])
                ))->show();

        if ($this->addable_options){
            $html .= (new AtomicElement(
                new FieldInfo([
                    'text' => '<div id="add-tag-widget" class="d-flex gap-2 mt-2 mb-2">
                        <input type="text" id="new-tag-name"
                        class="form-control form-control-sm" placeholder="New tag">
                        <button type="button" id="add-tag-btn" 
                        class="btn btn-sm btn-secondary">Add tag</button>
                        </div>'])
            ))->show();
        }

        // Add checkboxgroup
        $html .= '<div class="checkbox_group">';

        HtmlUtils::dump("options SearchableCheckboxes", $this->options);
        foreach ($this->options as $checkbox_info) {
            $html .= '<div class="checkbox_container">';
            $html .= (new Checkbox($checkbox_info))->show();
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html .= "</div>";
    }
}
