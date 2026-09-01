<?php

namespace Wiki\controllers\validators;

use Wiki\tools\interfaces\iValidator,
    Wiki\tools\utils\Utils,
    Wiki\tools\traits\tErrorMessageCollector;


class SortValidator implements iValidator
{
    use tErrorMessageCollector;
    protected array $field_inputs = [];
    private array $sort_values = ["lastEdit" ,"rating"];
    
    public function validate(string $name): bool
    {
        $this->field_inputs[$name] = Utils::getRequestVar(
            key: $name,
            frompost: true
        );       
        return $this->validateFields(field_inputs: $this->field_inputs);
    }

    public function getFieldInputs(): array
    {
        return $this->field_inputs;
    }


    public function validateFields(array $field_inputs): bool
    {
        if (in_array($field_inputs['sortby'],$this->sort_values))
            {
                return true;
            }
        else
            {
                $this ->logError("Not a valid sorting method");
                return false;
            }
    }
}