<?php

/**
 * Validator class for the registration form.
 */
class RegisterValidator extends BaseValidator
{
    /**
     * Register page-specific validation behaviour. Check if both passwords are the same.
     * @param array $field_inputs
     * @return bool
     */
    public function validateFields(array $field_inputs): bool
    {
        //Check if password1 and password2 are equal
        return $field_inputs['password'] === $field_inputs['verifypassword'];
    }
}