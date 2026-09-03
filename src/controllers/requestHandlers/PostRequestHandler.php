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
        $_SESSION['errors'] = [];
        $_SESSION['messages'] = [];

        // Validate the posted Form
        // Get field type and name
        $field_info = ModelSelector::getFormModel()
            ->fetchFieldInfo(page_name: $this->response['page']);

        // Perform basic validation on contact fields, field inputs are retrieved internally
        // $validaton_result will contain keys ['ok', 'user_error', 'field_inputs']
        $validation_result = (new ValidationHandler)
            ->validateFields(field_info: $field_info);

        // If form was submitted correctly WRONG: add validation errors to response
        $_SESSION['errors'] = array_merge($_SESSION['errors'], $validation_result['user_error']);
        // If form was submmitted CORRECT: get page-specific behaviour
        // Get page-speicifc behaviour
        switch ($request['page']) {
            case 'register':
                if ($validation_result['ok']) {

                    // handle if registration is allowed
                    $validation_result = UserHandler::getInstance()
                        ->handleRegistration(validation_result: $validation_result);

                    // Add all (if any) error messages to the response array
                    $_SESSION['errors'] = array_merge($_SESSION['errors'], $validation_result['user_error']);

                    // Registration was successful
                    if ($validation_result['ok'] !== false) {
                        $this->response['page'] = 'login';
                        $_SESSION['messages'][] = 'Registration was successful!';
                    }
                }
                break;

            case 'login':
                if ($validation_result['ok']) {
                    // Validate user inputs and on succes: get logged-in user's info
                    $validation_result = UserHandler::getInstance()
                        ->handleUserLogin(validation_result: $validation_result);

                    // Add all (if any) error messages to the response array
                    $_SESSION['errors'] = array_merge($_SESSION['errors'], $validation_result['user_error']);

                    // If log in was succesful, if the login was unsuccesful, errors are stored in $response
                    if ($validation_result['ok']) {
                        // Update response
                        $this->response['page'] = 'home';
                        $this->response['isLoggedIn'] = true;
                        $_SESSION['messages'][] = 'Login was successful!';
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
                    $_SESSION['errors'] = array_merge($_SESSION['errors'], $validation_result['user_error']);
                    if ($validation_result['ok']) {
                        $_SESSION['messages'][] = 'User information is saved!';
                    }
                }
                break;
            case 'search':
                $this->response['Tag'] = $validation_result['field_inputs']['Tag'] ?? [];
                $this->response['Author'] = $validation_result['field_inputs']['Author'] ?? [];
                $this->response['sortby'] = $validation_result['field_inputs']['sortby'];
                $this->response['field_values'] = $this->arrayToMarkedArray($validation_result['field_inputs'],['Tag', 'Author']);

                break;
            case 'rateArticle':
                // This is actually an ajax function
                break;
            case 'dashboard':
                break;
            case 'editArticle':
                $this->response['editArticleID'] = Utils::getRequestVar('articleID', true);
                $this->response['userID'] = Utils::getSesVar('userID');
                $this->response['bodyinfo']['title'] = $validation_result['field_inputs']['title'];
                $this->response['bodyinfo']['summary'] = $validation_result['field_inputs']['summary'];
                $this->response['bodyinfo']['codeBlock'] = $validation_result['field_inputs']['codeBlock'];
                $this->response['field_values'] = $this->arrayToMarkedArray($validation_result['field_inputs'],['existing_tag']);

                if ($validation_result['ok']) {
                    // This is the post request for editing or saving a (new) article
                    if (Utils::getRequestVar('action', true) == 'saveArticle') {

                        $validation_result = ArticleHandler::getInstance()
                            ->handleArticleSubmission(
                                validation_result: $validation_result,
                                article_id: $this->response['editArticleID'],
                                user_id: $this->response['userID']
                            );

                        if (isset($validation_result['field_inputs']['new_article_id'])) {
                            $this->response['editArticleID'] = $validation_result['field_inputs']['new_article_id'];
                        }

                        if ($validation_result['ok']) {
                            $this->response['page'] = 'article';
                            $this->response['articleID'] = $this->response['editArticleID'];
                            $_SESSION['messages'][] = 'Article has been submitted!';
                        }

                    }
                    // This is a post request for creating a new article
                    else {
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


    /**
     * changes the array with given keys to values for the checkboxgroup to mark
     * so example : [tag5 => 5] becomes [5 => 1] 
     * @param array $result_array array containing the arrays that need to be changes.
     * @param array $keys is an array with key names used to find the array that have to be changed.
     * @return array the marked array 
     */
    protected function arrayToMarkedArray(array $result_array, array $keys)
    {
        $marked_array = [];

        foreach ($keys as $key) {
            $marked_array[$key] = [];
            foreach ($result_array[$key] ?? [] as $value) {
                $marked_array[$key][$value] = '1';
            }
        }

        return $marked_array;

    }
}
