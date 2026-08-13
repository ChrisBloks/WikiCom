<?php

class ValidatorFactory
{


    public function createValidator(array $field_def)
    {
        foreach (['type', 'name', 'class', 'label'] as $key) {
            if (!array_key_exists($key, $field_def)) {
                throw new InvalidArgumentException("Field definition missing for key: '$key'");
            }
        }

        $posted = ($_SERVER['REQUEST_METHOD'] === 'POST');
        if ($posted) {
            switch ($field_def['type']) {
                case 'textarea':

                case 'checkboxgroup':

                case 'select':

                default:

            }
        }
    }
}
