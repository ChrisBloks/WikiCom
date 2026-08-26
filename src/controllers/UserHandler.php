<?php

namespace Wiki\controllers;

// use Wiki\tools\interfaces\iValidator;
use Wiki\tools\traits\tSingleton,
Wiki\models\ModelSelector,
Wiki\controllers\validators\BaseValidator,
Wiki\controllers\validators\Validator;


/**
 * Handler (controller) class for validating contact, login, and registration form submissions.
 * @uses tSingleton
 */
class UserHandler
{
    use tSingleton;

    /**
     * Check if login fields were correctly filled in. 
     * Performs basic validation, then attempts to match an email and password to the inputs.
     * Stores errors in the (passed by reference) $response parameter.
     * @param array $response array containing the source page (string)
     * @param Validator $validator BaseValidator object for first line validation.
     * @return array|false
     */
    public function checkLogin(array &$response, Validator $validator): array|false
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        // Check first line validation
        $result = $validator->useValidators($field_info);
        if ($result) {
            $userinfo = ModelSelector::getUserInfoModel()->fetchUserInfoByEmail($result['email']);
            // If email exists AND the corresponding password matches 
            if ($userinfo !== false && password_verify($result['password'], $userinfo['password'])) {
                return $userinfo;
            }
            // Email/Password could not be matched
            else {
                $response['error'] = "Login email or password is wrong!";
                return false;
            }
            // First line validation failed
        } else {
            $response['error'] = $validator->getErrors(); // Get why validation failed
            return false;

        }
    }

    /**
     * Check if registration fiels were filled in correctly.
     * On success adds a new user to the database. 
     * Fails if any of the fields contained invalid inputs or if the given email already exists in the database.
     * @param array $response array containing the source page (string)
     * @param BaseValidator $validator BaseValidator object for first line validation.
     * @return void
     */
    public function checkRegistration(array &$response, BaseValidator $validator): void
    {
        // Check first line validation
        if ($validator->validate($response['page'])) {
            $result = $validator->getFieldInputs();
            // Check if the email does NOT exist
            if (!ModelSelector::getUserInfoModel()->checkEmailExists($result['email'])) {
                // Add new user to the database
                ModelSelector::getUserInfoModel()->saveUser(
                    username: $result['name'],
                    password: $result['password'],
                    email: $result['email']
                );
            }
            // Email was found in the database 
            else {
                $response['error'] = "Email already exists!";
            }
        }
        // First line validation failed 
        else {
            $response['error'] = $validator->getErrors();
        }
    }

    
    /**
     * Checks if the contact form was correctly filled in and saves the contact to the database.
     * @param array $response array containing the source page (string)
     * @param BaseValidator $validator BaseValidator object for first line validation.
     * @return void
     */
    public function checkContact(array &$response, BaseValidator $validator): void
    {
        if ($validator->validate($response['page'])) {
            $result = $validator->getFieldInputs();
            ModelSelector::getWebsiteInfoModel()->saveContact(
                name: $result['name'],
                email: $result['email'],
                message: $result['message']
            );
        } else {
            $response['error'] = $validator->getErrors();
        }
    }

    public function changeAboutInfo()
    {
    }
}
