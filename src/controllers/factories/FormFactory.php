<?php

namespace Wiki\controllers\factories;

use Wiki\views\containers\Form;

class FormFactory
{
    /*
    *
    */
    public function createForm(array $form_info, array $field_info, array $hidden_field_info, array $field_text = [], string $class = ""): Form
    {
        $field_factory = new FieldFactory();
        $form = new Form(
            action: $form_info['action'],
            method: $form_info['method'],
            submit_caption: $form_info['submit_caption'],
            enctype: $form_info['enctype'],
            
        );

        foreach ($hidden_field_info as $field_name => $field_value) {
            $form->addHiddenField($field_name, $field_value);
        }

        foreach ($field_info as $field_def) {
            $text = (isset($field_text[$field_def['name']]) ? $field_text[$field_def['name']] : "");
            $form->addElement($field_factory->createField(
                field_def: $field_def,
                field_text: $text
            ));
        }
        return $form;
    }
}
