<?php

namespace Wiki\controllers;

// use Wiki\tools\interfaces\iValidator;
use Wiki\tools\traits\tSingleton,
    Wiki\models\ModelSelector,
    Wiki\controllers\validators\BaseValidator;

class UserHandler
{
    use tSingleton;


    /**
     * Check if login fields were correctly filled in. 
     * First performs basic validation, then attempts to match an email and password to the inputs.
     * Stores errors in the (passed by reference) $response parameter.
     * @param array $response array containing the source page (string)
     * @param BaseValidator $validator BaseValidator object for first line validation.
     * @return array|false
     */
    public function checkLogin(array &$response, BaseValidator $validator): array|false
    {
        // Check first line validation
        if ($validator->validate($response['page'])) {
            $result = $validator->getFieldInputs();
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
            $response['error'] = $validator->getErrors();
            return false;
        }
    }

    public function checkRegistration(array &$response, BaseValidator $validator): void
    {
        if ($validator->validate($response['page'])) {
            $result = $validator->getFieldInputs();
            if (ModelSelector::getUserInfoModel()->checkEmail($result['email'])) {
                ModelSelector::getUserInfoModel()->registerUser(
                    username: $result['name'],
                    password: $result['password'],
                    email: $result['email']
                );
            } else {
                $response['error'] = "Email already exists!";
            }
        } else {
            $response['error'] = $validator->getErrors();
        }
    }


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

    public function changeAboutInfo() {}
}
