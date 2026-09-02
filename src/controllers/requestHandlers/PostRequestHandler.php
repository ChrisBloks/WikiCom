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
                                article_id: $this->response['editArticleID']
                            );

                        if (isset($validation_result['field_inputs']['filevar'])) {
                            // Construct image file path
                            $target_dir = \Config::ARTICLEIMGPATH;
                            $filevar = $validation_result['field_inputs']['filevar'];
                            $filetype = strtolower(pathinfo($filevar['name'], PATHINFO_EXTENSION));
                            $filename = 'article_' . $this->response['editArticleID'] . '.' . $filetype . '';
                            $target_file = $target_dir . $filename;

                            // uploading image
                            if (!move_uploaded_file($filevar["tmp_name"], $target_file)) {
                                $validation_result['ok'] = false;
                                $this->response['user_error'][] = "Sorry, there was an error uploading your file.";
                            } else {
                                $validation_result['field_inputs']['articleimg'] = $filename;
                            }
                        }


                        if ($validation_result['ok']) {
                            // Adds new article to the data base and returns the new article id, which is stored in the response array
                            if ($this->response['editArticleID'] == 0) {
                                $new_article_id = ModelSelector::getArticleModel()->saveNewArticleInfo(
                                    article_title: $validation_result['field_inputs']['title'],
                                    article_summary: $validation_result['field_inputs']['summary'],
                                    article_codeBlock: $validation_result['field_inputs']['codeBlock'] ?? '',
                                    imgFileName: $validation_result['field_inputs']['articleimg'] ?? '',
                                    user_id: $this->response['userID']
                                );
                                if ($new_article_id === false) {
                                    $this->response['user_error'][] = 'Something went wrong while saving the article. Please try again later.';
                                } else {
                                    $this->response['articleID'] = $new_article_id;
                                    $this->response['page'] = 'article';
                                }
                            } else {
                                //if no article image given check if article already has an image, if so keep it.
                                if (empty($validation_result['field_inputs']['articleimg'])) {
                                    $article_info = ModelSelector::getArticleModel()->fetchArticleById($this->response['editArticleID']);
                                    $validation_result['field_inputs']['articleimg'] = $article_info['imgFileName'];
                                }



                                // Updates existing article in the database
                                $update_result = ModelSelector::getArticleModel()->saveExistingArticleInfo(
                                    article_id: $this->response['editArticleID'],
                                    article_title: $validation_result['field_inputs']['title'],
                                    article_summary: $validation_result['field_inputs']['summary'],
                                    article_codeBlock: $validation_result['field_inputs']['codeBlock'] ?? '',
                                    imgFileName: $validation_result['field_inputs']['articleimg'] ?? '',
                                    user_id: $this->response['userID']
                                );
                                if ($update_result === false) {
                                    $this->response['user_error'][] = 'Something went wrong while updating the article. Please try again later.';
                                }
                            }
                        } else {
                            $this->response['user_error'] = array_merge($this->response['user_error'], $validation_result['user_error']);
                        }

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

        HtmlUtils::dump('response',$this->response);
        return $this->response;
    }
}
