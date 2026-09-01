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
     * @param array $response array containing the source page (string)
     * @param ValidationHandler $validator BaseValidator object for first line validation.
     * @return array|false
     */
    public function checkLogin(array $response, ValidationHandler $validator): array
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo(page_name: $response['page']);
        // $result will contain keys ['ok', 'userErr', 'field_inputs']
        $result = $validator->validateFields(field_info: $field_info);

        // All fields cotain valid inputs
        if ($result['ok']) {
            $userinfo = ModelSelector::getUserInfoModel()
                ->fetchUserInfoByEmail(email: $result['field_inputs']['email']);
            // Store user info in the result
                $result['userInfo'] = $userinfo;

            // Query failed!
            if ($userinfo === false){
                $result['ok'] = false;
                $result['userErr'][] = "Something went wrong with the server! contact Marius";
            }

            // If no corresponding email was found in the database OR 
            // if given password does not match stored password (hashed)
            else if (empty($userinfo) || !password_verify($result['field_inputs']['password'], $userinfo['password'])) {   
                $result['ok'] = false;
                $result['userErr'][] = "Login email or password is wrong!";
            }
        } 
        // Some field contained an invalid input
        else {  
            $result['ok'] = false;
            $result['userError'] = array_merge($response['userError'], $validator->getErrors()); // Get why validation failed
        }
        return $result;
    }

    /**
     * Check if registration fiels were filled in correctly.
     * On success adds a new user to the database. 
     * Fails if any of the fields contained invalid inputs or if the given email already exists in the database.
     * @param array $response array containing the source page (string)
     * @param ValidationHandler $validator BaseValidator object for first line validation.
     * @return array
     */
    public function checkRegistration(array $response, ValidationHandler $validator): array
    {
        // Validate page fields
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo(page_name: $response['page']);
        // $result will contain keys ['ok', 'userErr', 'field_inputs']
        $result = $validator->validateFields(field_info: $field_info);

        // All fields correctly filled in
        if ($result['ok']) {

            // Check if the email is present in the database
            // Returns an array or false
            $existing_user_id = ModelSelector::getUserInfoModel()->fetchUserIDbyEmail(email: $result['field_inputs']['email']);
            
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
            $result['userErr'] = array_merge($result['userErr'], $validator->getErrors());
        }

        return $result;
    }


    /**
     * Checks if the contact form was correctly filled in and saves the contact to the database.
     * @param array $response array containing the source page (string)
     * @param ValidationHandler $validator ValidationHandler object for first line validation.
     * @return array
     */
    public function checkContact(array $response, ValidationHandler $validator): array
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo(page_name: $response['page']);
        // Perform basic validation on contact fields
        // $result will contain keys ['ok', 'userErr', 'field_inputs']
        $result = $validator->validateFields(field_info: $field_info);

        return $result;
    }

    
    /**
     * checkAboutInfo TODO: add documentation
     * @param array $response
     * @param ValidationHandler $validator
     * @return array
     */
    public function checkAboutInfo(array $response, ValidationHandler $validator): array
    {
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        
        // Perform basic validation on contact fields
        // $result will contain keys ['ok', 'userErr', 'field_inputs']
        $result = $validator->validateFields(field_info: $field_info);
        
        return $result;
    }
}
