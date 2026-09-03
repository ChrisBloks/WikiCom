<?php

namespace Wiki\controllers\requestHandlers;

use Wiki\tools\utils\Utils,
Wiki\tools\utils\HtmlUtils,
Wiki\controllers\UserHandler,
Wiki\controllers\ArticleHandler,
Wiki\controllers\ValidationHandler,
Wiki\models\ModelSelector;
use Monolog\Test\TestCase;

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

        // If form was submitted correctly WRONG: add validation errors to response
        $this->response['user_error'] = array_merge($this->response['user_error'], $validation_result['user_error']);
        // If form was submmitted CORRECT: get page-specific behaviour
        // Get page-speicifc behaviour
        switch ($request['page']) {
            case 'register':
                if ($validation_result['ok']) {

                    // handle if registration is allowed
                    $validation_result = UserHandler::getInstance()
                        ->handleRegistration(validation_result: $validation_result);

                    // Add all (if any) error messages to the response array
                    $this->response['user_error'] = array_merge($this->response['user_error'], $validation_result['user_error']);

                    // Registration was successful
                    if ($validation_result['registration_result'] !== false) {
                        $this->response['page'] = 'login';
                    }
                }
                break;

            case 'login':
                if ($validation_result['ok']) {
                    // Validate user inputs and on succes: get logged-in user's info
                    $validation_result = UserHandler::getInstance()
                        ->handleUserLogin(validation_result: $validation_result);

                    // Add all (if any) error messages to the response array
                    $this->response['user_error'] = array_merge($this->response['user_error'], $validation_result['user_error']);

                    // If log in was succesful, if the login was unsuccesful, errors are stored in $response
                    if ($validation_result['ok']) {
                        // Update response
                        $this->response['page'] = 'home';
                        $this->response['isLoggedIn'] = true;
                    }
                }
                break;

            case 'about':
                $this->response['aboutID'] = Utils::getRequestVar('author', false);
                $this->response['userID'] = Utils::getSesVar('userID');

                if ($validation_result['ok']) {
                    // checks if image is correct and saves about info
                    $validation_result = UserHandler::getInstance()->handleUserAboutInfo($validation_result);
                    // if there are any errors save them in response
                    $this->response['user_error'] = array_merge($this->response['user_error'], $validation_result['user_error']);
                }
                break;
            case 'search':
                $this->response['Tag'] = $validation_result['field_inputs']['Tag'] ?? [];
                $this->response['Author'] = $validation_result['field_inputs']['Author'] ?? [];
                $this->response['sortby'] = $validation_result['field_inputs']['sortby'];

                $checkboxgroups = ['Tag', 'Author'];
                $field_values = [];

                foreach ($checkboxgroups as $group) {
                    $field_values[$group] = [];
                    foreach ($this->response[$group] ?? [] as $value) {
                        $field_values[$group][$value] = '1';
                    }
                }
                $this->response['field_values'] = $field_values;

                break;
            case 'rateArticle':
                // This is actually an ajax function
                break;
            case 'dashboard':
                break;
            case 'editArticle':
                $this->response['page'] = 'editArticle';
                $this->response['editArticleID'] = Utils::getRequestVar('articleID', true);
                $this->response['userID'] = Utils::getSesVar('userID');
                $this->response['field_inputs'] = $validation_result['field_inputs'];
                if ($validation_result['ok']) {
                    // This is the post request for editing or saving a (new) article
                    if (Utils::getRequestVar('action', true) == 'saveArticle') {

                        $validation_result = ArticleHandler::getInstance()
                            ->handleArticleSubmission(
                                validation_result: $validation_result,
                                article_id: $this->response['editArticleID'],
                                $this->response['userID']
                            );

                    } else {
                        // This is a post request for creating a new article
                        $this->response['editArticleID'] = 0;
                    }
                }
                break;
            case 'contact':
                // On succesful contact form validation, save input to the database
                $field_inputs = $validation_result['field_inputs'];
                ModelSelector::getWebsiteInfoModel()->saveContact(
                    name: $field_inputs['name'],
                    email: $field_inputs['email'],
                    message: $field_inputs['message']
                );
                break;
        }

        return $this->response;
    }
}
