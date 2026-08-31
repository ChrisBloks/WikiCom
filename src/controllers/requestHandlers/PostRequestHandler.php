<?php

namespace Wiki\controllers\requestHandlers;

use Wiki\tools\utils\Utils,
Wiki\tools\utils\HtmlUtils,
Wiki\controllers\UserHandler,
Wiki\controllers\ArticleHandler,
Wiki\controllers\ValidationHandler,
Wiki\models\ModelSelector;

class PostRequestHandler extends BaseRequestHandler
{
    public function handleRequest(array $request): array
    {
        $this->response = $request;
        $this->response['userError'] = [];

        switch ($request['page']) {
            case 'register':

                // Validate user inputs on the registration form
                // contains keys ['ok', 'userErr', 'field_inputs']
                $validation_result = UserHandler::getInstance()
                    ->checkRegistration(
                        response: $this->response,
                        validator: new ValidationHandler()
                    );
                
                // Add all (if any) error messages to the response array
                $this->response['userError'] = array_merge($this->response['userError'], $validation_result['userErr']);

                // If validation was succesful, add new user to the database
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
                        $this->response['page'] = 'login';
                    }
                    // Store model errors 
                    else{
                        $this->response['userError'] = array_merge($this->response['userError'], ModelSelector::getUserInfoModel()->getErrors());
                    }
                }
                break;

            case 'login':
                // Validate user inputs and on succes: get logged-in user's info
                $validation_result = UserHandler::getInstance()
                    ->checkLogin(
                        response: $this->response,
                        validator: new ValidationHandler()
                    );

                // Add all (if any) error messages to the response array
                $this->response['userError'] = array_merge($this->response['userError'], $validation_result['userErr']);

                // If log in was succesful, if the login was unsuccesful, errors are stored in $response
                if ($validation_result['ok'] !== false) {
                    $user_info = $validation_result['field_inputs'];
                    // Update session variables
                    $_SESSION['userName'] = $user_info['name'];
                    $_SESSION['userID'] = $user_info['id'];

                    // Update response
                    $this->response['page'] = 'home';
                    $this->response['isLoggedIn'] = true;
                }
                break;

            case 'about':
                $this->response['aboutID'] = Utils::getRequestVar('author', false);
                $this->response['userID'] = Utils::getSesVar('userID');

                
                $validation_result = UserHandler::getInstance()
                    ->checkAboutInfo(
                        response: $this->response,
                        validator: new ValidationHandler()
                    );

                if ($validation_result['ok'] !== false) {
                    $about_info = $validation_result['field_inputs'];

                    // Construct image file path
                    $target_dir = \Config::AUTHORIMGPATH;
                    $filevar = $_FILES[$about_info['name']];
                    $filetype = strtolower(pathinfo($filevar['name'], PATHINFO_EXTENSION));
                    $filename = 'author_' . $this->response['aboutID'] . '.' . $filetype . '';
                    $target_file = $target_dir . $filename;

                    // uploading image
                    if (move_uploaded_file($filevar["tmp_name"], $target_file)) {
                        $result = ModelSelector::getUserInfoModel()
                            ->saveUserAboutInfo(
                                imgFileName: $filename,
                                description: $about_info['description'],
                                author_id: $this->response['aboutID']
                            );
                        if ($result == false) {
                            $this->response['userError'] = array_merge($this->response['userError'], ModelSelector::getUserInfoModel()->getErrors());
                        }
                    } else {
                        $this->response['userError'][] = "Sorry, there was an error uploading your file.";
                    }
                }
                break;

            case 'search':
                $search_info = ArticleHandler::getInstance()
                    ->checkSearch(
                        response: $this->response, 
                        validator: new ValidationHandler()
                    );

                if ($search_info !== false) {
                    // Update response
                    $this->response = array_merge($this->response, $search_info);
                }
                else{
                    $this->response['userError'] = array_merge($this->response['userError'], ModelSelector::getUserInfoModel()->getErrors());
                }



            // collect checkboxgroup van tags en authors
            // collect sortby rating/datum
            // give collected checkboxes and sort to pagefactory
            case 'rateArticle':
                // This is actually an ajax function
                break;
            case 'dashboard':
                $this->response['page'] = 'editArticle';
                $this->response['editArticleID'] = 0;
                break;
            case 'editArticle':
                // add validator
                $this->response['editArticleID'] = 0;
                // check user_id against db
                // if ok -> send article info to db
                break;
            case 'contact':
                // Contains ['ok', 'userErr', 'field_inputs']
                $validation_result = UserHandler::getInstance()
                    ->checkContact(
                        response: $this->response, 
                        validator: new ValidationHandler()
                    );

                // On succesful contact form validaiton, save input to the database
                if ($validation_result['ok'] !== false) {
                    $field_inputs = $validation_result['field_inputs'];
                    ModelSelector::getWebsiteInfoModel()->saveContact(
                        name: $field_inputs['name'],
                        email: $field_inputs['email'],
                        message: $field_inputs['message']
                    );
                }
                break;
        }

        return $this->response;
    }
}
