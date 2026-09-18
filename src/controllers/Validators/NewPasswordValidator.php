<?php

namespace Wiki\controllers\validators;

use Wiki\tools\utils\Utils,
    Wiki\Config;

/**
 * Validator class for the registration form.
 */
class NewPasswordValidator extends TextValidator
{
    public function validate(string $name, bool $optional = false, string $error_disp_name = ""): bool
    {
        if (empty($error_disp_name)) $error_disp_name = $name;
        
        // Get post variable based on name given
        $this->field_inputs['password_1'] = Utils::getRequestVar(
            key: 'password_1',
            frompost: true
        );

        $this->field_inputs['password_2'] = Utils::getRequestVar(
            key: 'password_2',
            frompost: true
        );

        // enforce string length of at least 4 characters
        if (strlen($this->field_inputs['password_1']) < \Config::MIN_PW_LENGTH) {
            $this->logError(message: 'Password must be at least 4 characters long.');
        }
        $all_empty = true;

        if (empty($this->field_inputs['password_1'])){
            if (!$optional) $this->logError(message: "Password is not filled in.");
        } else {
            $all_empty = false;
        }

        if (empty($this->field_inputs['password_2'])){
            if (!$optional) $this->logError(message: "Verify password is not filled in.");
        } else {
            $all_empty = false;
        }

        

        // toDo add check for optional
        if ($all_empty && $optional === true) {
            // echo 'all empty and optional';
            return true;
        }


        // If there are errors, return false, otherwise call the page-specific validator to check the values
        if ($this->hasErrors()) {
            return false;
        } else {
            return $this->validateFields(field_inputs: $this->field_inputs, error_disp_name: $error_disp_name);
        }
    }

    /**
     * Register page-specific validation behaviour. Check if both passwords are the same.
     * @param array $field_inputs
     * @return bool
     */
    public function validateFields(array $field_inputs, string $error_disp_name): bool
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
