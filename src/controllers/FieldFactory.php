<?php

class FieldFactory
{


    public function createField(array $field_def): BaseField
    {
        foreach (['type', 'name', 'class', 'label'] as $key) {
            if (!array_key_exists($key, $field_def)) {
                throw new InvalidArgumentException("Field definition missing for key: '$key'");
            }
        }
        
        switch ($field_def['type']) {
            case 'textarea':
                return new TextAreaField(
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"]
                );
            case 'checkboxgroup':
                return new CheckBoxGroup(
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"],
                    options: $field_def['options']
                );
            case 'select':
                return new Select(
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"],
                    options: $field_def['options']                    
                );
            default:
                return new InputField(   
                    type: $field_def['type'],
                    name: $field_def["name"],
                    class: $field_def["class"],
                    label: $field_def["label"]
                );
        }
    }

}