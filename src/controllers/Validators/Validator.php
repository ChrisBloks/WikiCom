<?php
namespace Wiki\controllers\validators;

use Wiki\controllers\factories\ValidatorFactory,
    Wiki\tools\traits\tErrorMessageCollector,
    Wiki\tools\utils\HtmlUtils;
class Validator
{
    use tErrorMessageCollector;
    protected array $validatorlist = [];
    protected array $field_inputs = [];

    /**
     * Collects the needed validator objects needed
     * Runs through the fields and based on type runs the correct validators
     * If fails save the error message otherwise save the field input and return the array if no errors
     * otherwise return false
     * @param array $field_info array containing info about fields
     * @return array|false
     */
    public function validateFields(array $field_info): array|false
    {
        // Populate the validatorList with the required validator instances
        $this->getValidators($field_info);

        // Loop through the fields and uses the correct validator
        foreach ($field_info as $field) {
            $validation_result = $this->validatorlist[$field['type']]->validate($field['name']);
            // If validation is succesful
            if ($validation_result) {
                // Collect the user's inputs
                $this->field_inputs = array_merge($this->field_inputs, $this->validatorlist[$field['type']]->getFieldInputs());
            } 
            // If validation failed
            else {
                // Get all errors
                foreach ($this->validatorlist[$field['type']]->getErrors() as $error_message){
                    $this->logError("{$field_info['name']}: {$error_message}");
                }
            }
        }
        if ($this->hasErrors()) {
            return false;
        } else {
            return $this->field_inputs;
        }
    }
    /**
     * Populate the validatorList with validators based on field_info types.
     * @param array $field_info should contain ['type'] key
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