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

    public function validate(string $name): bool
    {
        $this->field_inputs[$name] = $_POST[$name];
    
        return $this->validateFields(field_inputs: $this->field_inputs);
    }

    public function getFieldInputs(): array
    {
        HtmlUtils::dump("test",$_POST);
        return $this->field_inputs;
    }

    public function validateFields(array $field_inputs): bool
    {
        // reset needed because field_inputs is an array in an array
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