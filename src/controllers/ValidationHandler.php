<?php

namespace Wiki\controllers;

use Wiki\controllers\factories\ValidatorFactory,
    Wiki\tools\traits\tErrorMessageCollector,
    Wiki\tools\utils\HtmlUtils,
    Wiki\tools\traits\tSingleton;

class ValidationHandler
{
    use tErrorMessageCollector;
    use tSingleton;
    protected array $validatorlist = [];
    protected array $field_inputs = [];

    /**
     * Collects the needed validator objects needed
     * Runs through the fields and based on type runs the correct validators
     * If fails save the error message otherwise save the field input and return the array if no errors
     * otherwise return false
     * @param array $field_info [int => ['type' => string, 'name' => string]]
     * @return array ['ok' => bool,
     *               'user_error' => [int => string],
     *               'field_inputs' => [field_name(string) => string]]
     */
    public function validateFields(array $field_info): array
    {
        // Populate the validatorList with the required validator instances
        $this->getValidators($field_info);

        // Loop through the fields and uses the correct validator
        foreach ($field_info as $field) {
            // Validate the field according to its type
            $validation_result = $this->validatorlist[$field['type']]
                ->validate(
                    name: $field['name'],
                    optional: $field['optional']
                );

            // Always add the user input to field_inputs
            $this->field_inputs = array_merge($this->field_inputs, $this->validatorlist[$field['type']]->getFieldInputs());
            // If validation failed
            if ($validation_result === false) {
                // Save all errors
                foreach ($this->validatorlist[$field['type']]->getErrors() as $error_message) {
                    $this->logError(
                        message: "{$error_message}"
                    );
                }
                // All errors have been saved to the ValidationHandler
                // Empty the validator's field (incase this validator will be used again)
                $this->validatorlist[$field['type']]->emptyErrors();
            }

        }

        return [
            'ok' => !$this->hasErrors(),
            'user_error' => $this->getErrors(),
            'field_inputs' => $this->field_inputs
        ];

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
                $this->validatorlist[$field['type']] = ValidatorFactory::From($field['type'])->createValidator();
            }
        }
    }
}
