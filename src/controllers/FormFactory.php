<?php
require_once "./src/controllers/FieldFactory.php";
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/views/Form.php";
require_once "./src/views/fields/InputField.php";
require_once "./src/views/fields/TextAreaField.php";
class FormFactory
{


    public function createForm(array $form_info, array $field_info): Form{
        $field_factory = new FieldFactory();
        $form = new Form(
                        action: $form_info['action'],
                        method: $form_info['method'],
                        submit_caption: $form_info['submit_caption']
                    );
        
        foreach ($field_info as $field_def){
            $form->addElement($field_factory->createField($field_def));

        }
        return $form;

    }
}