<?php

namespace Wiki\views\fields;

use ArrayAccess;
use InvalidArgumentException;
use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;


class Checkbox extends BaseField
{
    protected bool $checked;

    public function __construct(array $field_info)
    {
        // Set properties
        parent::__construct(
            name: 'checkbox_'.$field_info['name'], 
            label: $field_info['name'],
            class: $field_info['class'] ?? "serachable tag_checkbox form-check-input", // TODO: Place this in the database somehow?
            value: $field_info['id'], // For a checkbox, its value should be equal to its id.
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
