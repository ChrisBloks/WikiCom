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
     * @param Validator $validator BaseValidator object for first line validation.
     * @return void
     */
    public function checkRegistration(array &$response, Validator $validator): array|false
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        // Check first line validation
        $result = $validator->useValidators($field_info);
        if ($result) {
            // Check if the email does NOT exist
            if (!ModelSelector::getUserInfoModel()->checkEmailExists($result['email'])) {
                return $result;
            }
            // Email was found in the database 
            else {
                $response['error'] = "Email already exists!";
                return false;
            }
        }
        // First line validation failed 
        else {
            $response['error'] = $validator->getErrors();
            return false;
        }
    }


    /**
     * Checks if the contact form was correctly filled in and saves the contact to the database.
     * @param array $response array containing the source page (string)
     * @param Validator $validator Validator object for first line validation.
     * @return array|false
     */
    public function checkContact(array &$response, Validator $validator): array|false
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        // Perform basic validation on contact fields
        $result = $validator->useValidators($field_info);
        if ($result) {
            return $result;

        }
        // If any contact field was not entered correctly
        else {
            $response['error'] = $validator->getErrors();
            return false;
        }
    }

    public function checkAboutInfo(array &$response, Validator $validator): array|false
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        
        // Perform basic validation on contact fields
        $result = $validator->useValidators($field_info);
        if ($result) {
            return $result;
        }
        // If any contact field was not entered correctly
        else {
            $response['error'] = $validator->getErrors();
            print_r($response);
            return false;
        }
    }
}
