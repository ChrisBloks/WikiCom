<?php

namespace Wiki\controllers\validators;


class EmailValidator extends TextValidator
{
    public function validateFields(array $field_inputs, string $error_disp_name = ""): bool
    {
        if (empty($error_disp_name)) $error_disp_name = $field_inputs['name'];

        if (filter_var($field_inputs['email'], FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            $this->logError("{$error_disp_name} contained an invalid Email");
            return false;
        }
    }
}
