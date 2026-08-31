<?php

namespace Wiki\controllers\validators;

use wiki\tools\utils\Utils;


class CheckBoxValidator extends BaseValidator
{    

    public function validate(string $name): bool
    {
        $this->field_inputs[$name] = $_POST[$name];
    
        return $this->validateFields(field_inputs: $this->field_inputs);
    }
    public function validateFields(array $field_inputs): bool
    {
        // reset needed becaue field_inputs is an array in an array
        $inputs = reset($field_inputs);
        foreach ($inputs as $value)
            if (!is_numeric($value))
            {
                $this ->logError("Check box is not numeric");
                return false;
            }
        return true;
    }
}