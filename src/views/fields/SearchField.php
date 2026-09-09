<?php

namespace Wiki\views\fields;

use Wiki\tools\utils\HtmlUtils;

class SearchField extends InputField {

    protected string $placeholder = "Search...";

    public function __construct(array $field_info) {

        parent::__construct(
            type: "text",
            name: $field_info['name'],
            class: $field_info['class'], // form-control search-input
            label: $field_info['label'],
            text: $field_info['text'] ?? "", // optional
            id: $field_info['id'] ?? "" // optional
        );

    }

    public function show(): string {
    return HtmlUtils::printLabel($this->id, $this->label)
            . '<input type="' . $this->type . '" 
                        name="' . $this->name . '" 
                        id="' . $this->id . '" 
                        value="' . $this->text . '" 
                        class="' . $this->class . '"
                        placeholder= "' . $this->placeholder . '">';
    }
}