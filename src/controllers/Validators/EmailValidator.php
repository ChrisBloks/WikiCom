<?php

namespace Wiki\controllers\validators;


class EmailValidator extends TextValidator
{
    public function validateFields(array $field_inputs): bool
    {
        if (filter_var($field_inputs['email'], FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            $this->logError("Not a valid Email");
            return false;
        }
    }
}
