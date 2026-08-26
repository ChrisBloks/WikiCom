<?php

namespace Wiki\controllers\factories;

use Wiki\views\fields\BaseField,
    Wiki\views\fields\TextAreaField,
    Wiki\views\fields\checkBoxGroup,
    Wiki\views\fields\Select,
    Wiki\views\fields\ButtonField,
    Wiki\views\fields\InputField,
    Wiki\views\fields\NewPassword;

class FieldFactory
{


    public function createField(array $field_def, ?string $field_text): BaseField
    {
        foreach (['type', 'name', 'class', 'label'] as $key) {
            if (!array_key_exists($key, $field_def)) {
                throw new \InvalidArgumentException("Field definition missing for key: '$key'");
            }
        }

        switch ($field_def['type']) {
            case 'textarea':
                return new TextAreaField(
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"],
                    text: $field_text,
                );
            case 'checkboxgroup':
                return new CheckBoxGroup(
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"],
                    options: $field_def['options'],
                    value: $field_def['value']
                );
            case 'select':
                return new Select(
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"],
                    options: $field_def['options']
                );
            case 'button':
                return new ButtonField(
                    type: $field_def['type'],
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"]
                );
            case 'new_password':
                return new NewPassword(
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"]
                );
            default:
                return new InputField(
                    type: $field_def['type'],
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"],
                    text: $field_text
                );
        }
    }
}
