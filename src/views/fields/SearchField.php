<?php

namespace Wiki\views\fields;

use Wiki\dataObjects\ElementInfo;
use Wiki\dataObjects\FieldInfo;
use Wiki\tools\utils\HtmlUtils;

class SearchField extends InputField {

    protected string $placeholder;

    public function __construct(ElementInfo $element_info) {
        parent::__construct(
            new ElementInfo([
                'name' => $element_info['name'] ?? "",
                'class' => $element_info['class'] ?? "", // form-control search-input
                'id' => $element_info['id'] ?? "", // optional
                'field_info' => new FieldInfo([
                    'type' => "text",
                    'label' => $element_info['field_info']['label'] ?? "", // optional
                    'text' => $element_info['field_info']['text'] ?? "", // optional
            ])

            ])
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