<?php

namespace Wiki\controllers;

// use Wiki\tools\interfaces\iValidator;
use Wiki\tools\traits\tSingleton,
Wiki\models\ModelSelector,
Wiki\controllers\validators\BaseValidator,
Wiki\controllers\validationHandler;
use Wiki\tools\utils\HtmlUtils;

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
     * @param ValidationHandler $validator BaseValidator object for first line validation.
     * @return array|false
     */
    public function checkLogin(array &$response, ValidationHandler $validator): array|false
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        // Check first line validation
        $result = $validator->validateFields($field_info);

        if ($result) {
            $userinfo = ModelSelector::getUserInfoModel()->fetchUserInfoByEmail($result['email']);
            // If email exists AND the corresponding password matches 
            if ($userinfo !== false && password_verify($result['password'], $userinfo['password'])) {
                return $userinfo;
            }
            // Email/Password could not be matched
            else {

                $response['userError'][] = "Login email or password is wrong!";
                return false;
            }
            // First line validation failed
        } else {
            $response['userError'] = array_merge($response['userError'], $validator->getErrors()); // Get why validation failed
            return false;

        }
    }

    /**
     * Check if registration fiels were filled in correctly.
     * On success adds a new user to the database. 
     * Fails if any of the fields contained invalid inputs or if the given email already exists in the database.
     * @param array $response array containing the source page (string)
     * @param ValidationHandler $validator BaseValidator object for first line validation.
     * @return array|false
     */
    public function checkRegistration1(array &$response, ValidationHandler $validator): array|false
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        // Check first line validation
        $result = $validator->validateFields($field_info);
        if ($result) {
            // Check if the email does NOT exist
            if (!ModelSelector::getUserInfoModel()->checkEmailExists($result['email'])) {
                
                // if model result -> false -> model
                return $result;
            }
            // Email was found in the database 
            else {
                $response['userError'][] = "Email already exists!";
                return false;
            }
        }
        // First line validation failed 
        else {
            $response['userError'] = array_merge($response['userError'], $validator->getErrors());
            return false;
        }
    }

    /**
     * Check if registration fiels were filled in correctly.
     * On success adds a new user to the database. 
     * Fails if any of the fields contained invalid inputs or if the given email already exists in the database.
     * @param array $response array containing the source page (string)
     * @param ValidationHandler $validator BaseValidator object for first line validation.
     * @return array|false
     */
    public function checkRegistration(array $response, ValidationHandler $validator): array|false
    {
        // Validate page fields
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        // $result will contain keys ['ok', 'userErr', 'field_inputs']
        $result = $validator->validateFields($field_info);

        // All fields correctly filled in
        if ($result['ok']) {

            // Check if the email is present in the database
            // Returns an array or false
            $existing_user_id = ModelSelector::getUserInfoModel()->fetchUserIDbyEmail($result['field_inputs']['email']);
            
            // Query failed!
            if ($existing_user_id === false){
                $result['ok'] = false;
                $result['userErr'][] =  ModelSelector::getUserInfoModel()->getErrors();
            }

            // ($existing_user_id is an array)
            else if (!empty($existing_user_id)) {
                $result['ok'] = false;
                $result['userErr'][] =  'email already in use!';
            }
        }
        // ($result == false) Fields were incorrectly filled in.
        else {
            $result['ok'] = false;
            // Add validator errors to the userError array
            $result['userErr'] = array_merge($result['userError'], $validator->getErrors());
        }

        return $result;
    }


    /**
     * Checks if the contact form was correctly filled in and saves the contact to the database.
     * @param array $response array containing the source page (string)
     * @param ValidationHandler $validator ValidationHandler object for first line validation.
     * @return array|false
     */
    public function checkContact(array &$response, ValidationHandler $validator): array|false
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        // Perform basic validation on contact fields
        $result = $validator->validateFields($field_info);
        if ($result) {
            return $result;

        }
        // If any contact field was not entered correctly
        else {
            $response['userError'] = array_merge($response['userError'], $validator->getErrors());
            return false;
        }
    }

    
    public function checkAboutInfo(array &$response, ValidationHandler $validator): array|false
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        
        // Perform basic validation on contact fields
        $result = $validator->validateFields($field_info);
        if ($result) {
            return $result;
        }
        // If any contact field was not entered correctly
        else {
            $response['userError'] = array_merge($response['userError'], $validator->getErrors());
            return false;
        }
    }
}
