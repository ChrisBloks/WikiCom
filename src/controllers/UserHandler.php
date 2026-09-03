<?php

namespace Wiki\controllers;

// use Wiki\tools\interfaces\iValidator;

use Wiki\controllers\ValidationHandler as ControllersValidationHandler;
use Wiki\tools\traits\tSingleton,
Wiki\models\ModelSelector,
Wiki\controllers\validators\BaseValidator,
Wiki\controllers\validationHandler,
Wiki\tools\utils\HtmlUtils,
Wiki\tools\utils\Utils;

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
     * @param array $validation_result array containing the name of the source page under key 'page'.
     * @return array contains keys ['ok', 'user_error', 'field_inputs']
     */
    public function handleUserLogin(array $validation_result): array
    {
        // Get user info associated with the given email
        $user_info = ModelSelector::getUserInfoModel()
            ->fetchUserInfoByEmail(email: $validation_result['field_inputs']['email']);
        // Store user info in the validation_result
        $validation_result['user_info'] = $user_info;

        // Query failed!
        if ($user_info === false) {
            $validation_result['ok'] = false;
            $validation_result['user_error'][] = "Something went wrong with the server! contact Marius";
        }

        // If no corresponding email was found in the database OR 
        // if given password does not match stored password (hashed)
        else if (empty($user_info) || !password_verify($validation_result['field_inputs']['password'], $user_info['password'])) {
            $validation_result['ok'] = false;
            $validation_result['user_error'][] = "Login email or password is wrong!";
        }

        if ($validation_result['ok']) {
            $user_info = $validation_result['user_info'];

            // Update session variables
            $_SESSION['userName'] = $user_info['name'];
            $_SESSION['userID'] = $user_info['id'];
        }

        return $validation_result;
    }

    /**
     * Check if registration fields were filled in correctly.
     * Fails if any of the fields contained invalid inputs or if the given email already exists in the database.
     * @param array $validation_result array containing the name of the source page under key 'page'.
     * @return array contains keys ['ok', 'user_error', 'field_inputs']
     */
    public function handleRegistration(array $validation_result): array
    {
        // Check if the email is present in the database
        // Returns an array or false
        $existing_user_id = ModelSelector::getUserInfoModel()->fetchUserIDbyEmail(email: $validation_result['field_inputs']['email']);

        // Query failed!
        if ($existing_user_id === false) {
            $validation_result['ok'] = false;
            $validation_result['user_error'][] = ModelSelector::getUserInfoModel()->getErrors();
        }

        // ($existing_user_id is an array)
        else if (!empty($existing_user_id)) {
            $validation_result['ok'] = false;
            $validation_result['user_error'][] = 'email already in use!';
        }

        if ($validation_result['ok']) {
            $user_info = $validation_result['field_inputs'];
            $registrationResult = ModelSelector::getUserInfoModel()
                ->saveUser(
                    username: $user_info['name'],
                    password: $user_info['password_1'],
                    email: $user_info['email']
                );
            // Registration was successful
            if ($registrationResult !== false) {
                $validation_result['ok'] = $registrationResult;
            }
            // Store model errors 
            else {
                $validation_result['user_error'] = array_merge($validation_result['user_error'], ModelSelector::getUserInfoModel()->getErrors());
            }
        }

        return $validation_result;
    }

    /**
     * Check if about fields were filled in correctly.
     * If everything is correct save new userabout info to database database.
     * @param array $validation_result array containing the name of the source page under key 'page'.
     * @return array contains keys ['ok', 'user_error', 'field_inputs']
     */
    public function handleUserAboutInfo(array $validation_result): array
    {
        $aboutID = Utils::getRequestVar('author', false);
        $about_info = $validation_result['field_inputs'];

        if (isset($validation_result['field_inputs']['filevar'])) {
            // Construct image file path
            $target_dir = \Config::AUTHORIMGPATH;
            $filevar = $validation_result['field_inputs']['filevar'];
            $filetype = strtolower(pathinfo($filevar['name'], PATHINFO_EXTENSION));
            $filename = 'author_' . $aboutID . '.' . $filetype . '';
            $target_file = $target_dir . $filename;

            // uploading image
            if (move_uploaded_file($filevar["tmp_name"], $target_file)) {
                $result = ModelSelector::getUserInfoModel()
                    ->saveUserAboutInfo(
                        imgFileName: $filename,
                        description: $about_info['description'],
                        author_id: $aboutID
                    );
                if ($result == false) {
                    $validation_result['user_error'] = array_merge($validation_result['user_error'], ModelSelector::getUserInfoModel()->getErrors());
                }
            } else {
                $validation_result['user_error'][] = "Sorry, there was an error uploading your file.";
            }
        }
        return $validation_result;

    }

}
