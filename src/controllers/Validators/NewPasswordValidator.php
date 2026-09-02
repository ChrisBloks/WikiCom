<?php

namespace Wiki\controllers\validators;

use Wiki\tools\utils\Utils;

/**
 * Validator class for the registration form.
 */
class NewPasswordValidator extends TextValidator
{
    public function validate(string $name, bool $optional = false): bool
    {
        // Get post variable based on name given
        $this->field_inputs['password_1'] = Utils::getRequestVar(
            key: 'password_1',
            frompost: true
        );

        $this->field_inputs['password_2'] = Utils::getRequestVar(
            key: 'password_2',
            frompost: true
        );
        // If field was left empty, log an error
        foreach ($this->field_inputs as $name => $field_input) {
            if (empty($field_input)) {
                if ($optional == false) {
                    $this->logError(message: 'Field ' . $name . ' was not filled in!');
                }
            }
        }


        // If there are errors, return false, otherwise call the page-specific validator to check the values
        if ($this->hasErrors()) {
            return false;
        } else {
            return $this->validateFields(field_inputs: $this->field_inputs);
        }
    }
    /**
     * Register page-specific validation behaviour. Check if both passwords are the same.
     * @param array $field_inputs
     * @return bool
     */
    public function validateFields(array $field_inputs): bool
    {
        //Check if password1 and password2 are equal
        if ($field_inputs['password_1'] === $field_inputs['password_2']) {
            return true;
        } else {
            $this->logError(message: 'Passwords are not the same');
            return false;
        }
    }
}
