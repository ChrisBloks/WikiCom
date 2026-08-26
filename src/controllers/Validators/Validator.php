<?php
namespace Wiki\controllers\validators;

use Wiki\controllers\factories\ValidatorFactory,
    Wiki\tools\traits\tErrorMessageCollector,
    Wiki\tools\utils\HtmlUtils;
class Validator
{
    use tErrorMessageCollector;
    protected array $validatorlist = [];
    protected array $field_Inputs = [];

    /**
     * Collects the needed validator objects needed
     * Runs through the fields and based on type runs the correct validators
     * If fails save the error message otherwise save the field input and return the array if no errors
     * otherwise return false
     * @param array $field_info array containing info about fields
     * @return array|false
     */
    public function useValidators(array $field_info): array|false
    {
        // Gets the correct validators
        $this->getValidators($field_info);
        // Loop through the fields and uses the correct validator
        foreach ($field_info as $field) {
            if ($this->validatorlist[$field['type']]->validate($field['name'])) {
                $this->field_Inputs = array_merge($this->field_Inputs, $this->validatorlist[$field['type']]->getFieldInputs());
            } else {
                $this->logError($this->validatorlist[$field['type']]->getErrors()[0]);
            }
        }
        if ($this->hasErrors()) {
            return false;
        } else {
            return $this->field_Inputs;
        }
    }
    /**
     * Goes through all the field types and based on those calls the page factory to create the correct fields
     * @param array $field_info array containing info about fields
     * @return void
     */
    protected function getValidators(array $field_info): void
    {
        foreach ($field_info as $field) {
            if (!array_key_exists($field['type'], $this->validatorlist)) {
                $this->validatorlist[$field['type']] = ValidatorFactory::from($field['type'])->createValidator();
            }
        }
    }

}