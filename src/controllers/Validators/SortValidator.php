<?php

namespace Wiki\controllers\validators;

use Wiki\tools\interfaces\iValidator,
    Wiki\tools\utils\Utils,
    Wiki\tools\traits\tErrorMessageCollector;
use Wiki\tools\utils\HtmlUtils;


class SortValidator implements iValidator
{
    use tErrorMessageCollector;
    protected array $field_inputs = [];
    private array $sort_values = ["lastEdit", "rating"];

    public function validate(string $name, bool $optional = false, ?string $error_disp_name = ""): bool
    {
        if (empty($error_disp_name)) $error_disp_name = $name;

        $this->field_inputs[$name] = Utils::getRequestVar(
            key: $name,
            frompost: true
        );
        return $this->validateFields(field_inputs: $this->field_inputs, error_disp_name: $error_disp_name);
    }

    public function getFieldInputs(): array
    {
        return $this->field_inputs;
    }


    public function validateFields(array $field_inputs, string $error_disp_name): bool
    {
        if (in_array($field_inputs['sortby'], $this->sort_values)) {
            return true;
        } else {
            $this->logError("{$error_disp_name} received an invalid sorting method");
            return false;
        }
    }
}
