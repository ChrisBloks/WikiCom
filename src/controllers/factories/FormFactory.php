<?php

namespace Wiki\controllers\factories;

use Wiki\tools\utils\HtmlUtils;
use Wiki\views\containers\Form;

/**
 * Factroy class for dynamically creating fields for the Wiki
 */
class FormFactory
{
    /**
     * Creates a Form object (including fields) which can be converted into HTML.
     * @param array $form_info should contain keys ['action', 'method', 'submit_caption', 'enctype']
     * @param array $field_info nested subarray where each subarray rerpresents a field's information. Each subarray should contain TODO: what should/may it contain? ['type', 'name']
     * @param array $hidden_field_info array of form ['field_name' => field_value]
     * @param array $field_text every field may (optionally) contain a text.
     * @param string $class name of the form class.
     * @param string $submit_class name of the submit button
     * @return Form
     */
    public function createForm(array $form_info, array $field_info, array $hidden_field_info, array $field_text = [], string $class = "", string $submit_class=''): Form
    {
        // Initialize the Form
        $form = new Form(
            action: $form_info['action'],
            method: $form_info['method'],
            submit_caption: $form_info['submit_caption'],
            class: $class,
            enctype: $form_info['enctype'],
            submit_class: $submit_class            
        );

        // Add hidden fields to the form.
        foreach ($hidden_field_info as $field_name => $field_value) {
            $form->addHiddenField($field_name, $field_value);
        }

        // Initialize FieldFactory to prepare for loop.
        $field_factory = new FieldFactory();
        // Loop over field info, in each iteration add a Field Object to Form.
        foreach ($field_info as $field_def) {
            // (optional) set field name
            $text = (isset($field_text[$field_def['name']]) ? $field_text[$field_def['name']] : "");
            // Create Field object
            $form->addElement($field_factory->createField(
                field_def: $field_def,
                field_text: $text
            ));
        }
        return $form;
    }


}
