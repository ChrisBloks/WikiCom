<?php

namespace Wiki\views\fields;

use Wiki\dataObjects\ElementInfo;
use Wiki\dataObjects\FieldInfo;
use Wiki\tools\utils\HtmlUtils;

class SearchField extends InputField {

    protected string $placeholder;

    public function __construct(ElementInfo $element_info) {
        // HtmlUtils::dump("searchField element_info", $element_info);
        // HtmlUtils::dump((isset($element_info['class']) ? 'Foo' : 'Bar'), $element_info['class']);
        parent::__construct($element_info);


        $this->placeholder = $element_info['field_info']['placeholder'] ?? 'Search...';
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