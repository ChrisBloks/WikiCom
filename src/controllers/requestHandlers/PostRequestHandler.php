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
    // TODO: add documentation
    // TODO: add validationHandler as dependency injection?
    public function handleRequest(array $request): array
    {
        $this->response = $request;
        $this->response['user_error'] = [];

        // Validate the posted Form
        // Get field type and name
        $field_info = ModelSelector::getFormModel()
            ->fetchFieldInfo(page_name: $this->response['page']);

        // Perform basic validation on contact fields, field inputs are retrieved internally
        // $validaton_result will contain keys ['ok', 'user_error', 'field_inputs']
        $validation_result = (new ValidationHandler)
            ->validateFields(field_info: $field_info);

        // TODO: remove
        HtmlUtils::dump('validation_result', $validation_result );

        // If form was submitted correctly WRONG: add validation errors to response
        if (!$validation_result['ok']) {
            $this->response['user_error'] = array_merge($this->response['user_error'], $validation_result['user_error']);
        } 
        // If form was submmitted CORRECT: get page-specific behaviour
        else {
            // Get page-speicifc behaviour
            switch ($request['page']) {
                case 'register':
                    // Check if registration is allowed
                    $validation_result = UserHandler::getInstance()
                        ->checkRegistration(validation_result: $validation_result);

                    // Add all (if any) error messages to the response array
                    $this->response['user_error'] = array_merge($this->response['user_error'], $validation_result['user_error']);

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
                        else {
                            $this->response['user_error'] = array_merge($this->response['user_error'], ModelSelector::getUserInfoModel()->getErrors());
                        }
                    }
                    break;

                case 'login':
                    // Validate user inputs and on succes: get logged-in user's info
                    $validation_result = UserHandler::getInstance()
                        ->checkLogin(validation_result: $validation_result);

                    // Add all (if any) error messages to the response array
                    $this->response['user_error'] = array_merge($this->response['user_error'], $validation_result['user_error']);

                    // If log in was succesful, if the login was unsuccesful, errors are stored in $response
                    if ($validation_result['ok']) {
                        $user_info = $validation_result['user_info'];

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

                    $about_info = $validation_result['field_inputs'];

                    // Construct image file path
                    $target_dir = \Config::AUTHORIMGPATH;
                    $filevar = $validation_result['field_inputs']['filevar'];
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
                            $this->response['user_error'] = array_merge($this->response['user_error'], ModelSelector::getUserInfoModel()->getErrors());
                        }
                    } 
                    else {
                        $this->response['user_error'][] = "Sorry, there was an error uploading your file.";
                    }
                    break;

                case 'search':
                    // broken
                    // $search_info = ArticleHandler::getInstance()
                    //    ->checkSearch(response: $this->response);
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
                    $this->response['editArticleID'] = 0;
                    break;
                case 'saveArticle':
                    $this->response['page'] = 'editArticle';
                    $this->response['editArticleID'] = $_POST['articleID']; ///testing purposes
                    $articleInfo = ArticleHandler::getInstance()
                        ->handleArticleSubmission(
                            response: $this->response,
                            validator: new ValidationHandler()
                        );
                    HtmlUtils::dump("articleInfo", $articleInfo);
                    break;
                case 'contact':
                    // On succesful contact form validaiton, save input to the database
                    $field_inputs = $validation_result['field_inputs'];
                    ModelSelector::getWebsiteInfoModel()->saveContact(
                        name: $field_inputs['name'],
                        email: $field_inputs['email'],
                        message: $field_inputs['message']
                    );
                    break;
            }
        }

        // TODO: remove
        HtmlUtils::dump('$this->response', $this->response);
        return $this->response;
    }
}
