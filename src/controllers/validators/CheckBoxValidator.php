<?php

namespace Wiki\controllers\validators;

use Wiki\tools\interfaces\iValidator,
    Wiki\tools\utils\Utils,
    Wiki\tools\traits\tErrorMessageCollector;
use Wiki\tools\utils\HtmlUtils;


class CheckBoxValidator implements iValidator
{    
    use tErrorMessageCollector;
    protected array $field_inputs = [];

    public function validate(string $name, bool $optional = false, ?string $error_disp_name = ""): bool
    {
        $this->field_inputs[$name] = isset($_POST[$name]) ? $_POST[$name]:array();
        if (empty($error_disp_name)) $error_disp_name = $name;

        if ($optional == false){
            if (empty($this->field_inputs[$name]))
            {
                $this -> logError("Please select at least one {$error_disp_name}");
                return false;
            }
        }
    
        return $this->validateFields(field_inputs: $this->field_inputs, error_disp_name: $error_disp_name);
    }

    public function getFieldInputs(): array
    {
        return $this->field_inputs;
    }

    public function validateFields(array $field_inputs, string $error_disp_name): bool
    {
        // reset needed because field_inputs is an array in an array
        $inputs = reset($field_inputs);
        if (!is_null($inputs)){
        foreach ($inputs as $value)
            if (!is_numeric($value))
            {
                $this ->logError("Check box is not numeric");
                return false;
            }
        }
        return true;
    }
}