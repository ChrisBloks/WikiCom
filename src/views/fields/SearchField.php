<?php

namespace Wiki\views\fields;

use Wiki\tools\utils\HtmlUtils;

class SearchField extends InputField {

    protected string $placeholder;

    public function __construct(array $field_info) {

        parent::__construct(
            type: "text",
            name: $field_info['name'],
            class: $field_info['class'], // form-control search-input
            label: $field_info['label'] ?? "", // optional
            text: $field_info['text'] ?? "", // optional
            id: $field_info['id'] ?? "" // optional
        );

        $this->placeholder = $field_info['placeholder'] ?? 'Search...';
    }

    public function show(): string {
    return (!empty($this->label) ? HtmlUtils::printLabel($this->id, $this->label) : "") .
            '<input type="' . $this->type . '" 
            name="' . $this->name . '" 
            id="' . $this->id . '" 
            value="' . $this->text . '" 
            class="' . $this->class . '"
            placeholder= "' . $this->placeholder . '">';
    }
}