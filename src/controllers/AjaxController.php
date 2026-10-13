<?php

namespace Wiki\controllers;

use BadMethodCallException;
use Wiki\tools\interfaces\iController,
Wiki\models\ModelSelector;
use Wiki\tools\utils\utils;
use Wiki\controllers\ArticleHandler;
use Wiki\controllers\factories\PageFactory;
use Wiki\tools\utils\HtmlUtils;

class AjaxController implements iController
{

    private bool $posted;
    private array $request;
    private array $response;


    public function __construct()
    {
        $this->posted = ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    public function handleRequest(): void
    {
        try {
            ob_start();

            $this->getRequest();
            $this->validateRequest();
            $this->showResponse();

            ob_end_flush();
        } catch (\Exception $e) {

            ob_end_clean();
            header('HTTP/1.1 500 Internal Server Error');
        }
    }


    // gather raw request data, same pattern as Controller::getRequest()
    private function getRequest(): void
    {
        $this->request = [
            'action' => Utils::getRequestVar(
                key: 'action',
                frompost: $this->posted,
                default: 'home'
            ),
            'id' => utils::getRequestVar('id', $this->posted, null),
            'userID' => utils::getSesVar('userID', -1),
            'isLoggedIn' => (utils::getSesVar('userID', -1) > 0)
        ];
    }

    // decide what to do based on the action, fill $this->request
    private function validateRequest(): void
    {
        if ($this->posted) {
            switch ($this->request['action']) {
                // ====================================================
                // POST CASES 
                // ====================================================
                case 'saveRating':
                    if (!$this->request['isLoggedIn']) {
                        throw new BadMethodCallException('Tried to save a rating without being logged in!');
                    } else {
                        $rating = utils::getRequestVar('rating', true, null);
                        $article_id = utils::getRequestVar('article_id', true, null);
                        $articleHandler = new ArticleHandler();
                        $rating_info = $articleHandler->handleSaveRating(
                            user_id: $this->request['userID'],
                            article_id: $article_id,
                            rating: $rating
                        );
                        $this->response = [
                            'avg_rating' => $rating_info["AVGrating"],
                            'n_ratings' => $rating_info["Nratings"]
                        ];
                    }
                    break;
                case 'deleteArticle':
                    $articleHandler = new ArticleHandler();
                    $delete_result = $articleHandler->handleDeleteArticle(
                        article_id: (int) $this->request['id'],
                        userId: (int) $_SESSION['userID']
                    );
                    if ($delete_result === false) {
                        throw new \Exception('Failed to delete article');
                    }
                    $this->response =
                        [
                            'success' => true,
                            'deleted rows' => $delete_result,
                        ];
                    break;
                case 'updateUserInfo':
                    $_SESSION['errors'] = [];
                    $_SESSION['messages'] = [];

                    $field_info = ModelSelector::getElementModel()
                        ->fetchFieldInfo(element_id: Utils::getRequestVar('element_id', true));

                    // Perform basic validation on contact fields, field inputs are retrieved internally
                    // $validaton_result will contain keys ['ok', 'user_error', 'field_inputs']
                    $validation_result = (new ValidationHandler)
                        ->validateFields(field_info: $field_info);

                    $_SESSION['errors'] = array_merge($_SESSION['errors'], $validation_result['user_error']);

                    if ($validation_result['ok'] && isset($validation_result['field_inputs'])) {
                        $result = UserHandler::getInstance()->handleUserInfoChange($validation_result);

                        $this->response = [
                            'success' => $result['ok'],
                            'errors' => $result['user_error'] ?? [],
                            'message' => $result['ok'] ? 'Your information has been updated.' : 'Something went wrong.',
                            'name' => $result['field_inputs']['name'] ?? null,
                            'userID' => $this->request['userID'] ?? null,
                            'email' => $result['field_inputs']['email'] ?? null,
                            'avatar_url' => $result['field_inputs']['avatar_url'] ?? null,
                        ];
                    } else {
                        $this->response = [
                            'success' => false,
                            'errors' => $validation_result['user_error'],
                            'message' => 'Please fix the errors below.',
                        ];
                    }
                    break;
                case 'updatePassword':
                    $_SESSION['errors'] = [];
                    $_SESSION['messages'] = [];

                    $field_info = ModelSelector::getElementModel()
                        ->fetchFieldInfo(element_id: Utils::getRequestVar('element_id', true));

                    // Perform basic validation on contact fields, field inputs are retrieved internally
                    // $validaton_result will contain keys ['ok', 'user_error', 'field_inputs']
                    $validation_result = (new ValidationHandler)
                        ->validateFields(field_info: $field_info);

                    $_SESSION['errors'] = array_merge($_SESSION['errors'], $validation_result['user_error']);

                    if ($validation_result['ok'] && isset($validation_result['field_inputs'])) {
                        $result = UserHandler::getInstance()->handleUserPasswordChange($validation_result);

                        $this->response = [
                            'success' => $result['ok'],
                            'errors' => $result['user_error'] ?? [],
                            'message' => $result['ok'] ? 'Password successfully changed.' : 'Something went wrong.',
                        ];
                    } else {
                        $this->response = [
                            'success' => false,
                            'errors' => $validation_result['user_error'],
                            'message' => 'Please fix the errors below.',
                        ];
                    }

                    break;
                case 'searchArticle':
                    $field_info = ModelSelector::getElementModel()
                        ->fetchFieldInfo(element_id: Utils::getRequestVar('element_id', true));

                    $validation_result = (new ValidationHandler)
                        ->validateFields(field_info: $field_info);

                    if ($validation_result['ok'] && isset($validation_result['field_inputs'])) {

                        $this->response['validation_result'] = $validation_result;

                        $this->response['validation_result'] = $validation_result;

                        $this->response['articles_info'] = ModelSelector::getArticleModel()->fetchArticleBySearch(
                            author_ids: $validation_result['field_inputs']['Author'] ?? [],
                            tag_ids: $validation_result['field_inputs']['Tag'] ?? [],
                            sortBy: $validation_result['field_inputs']['sortby'] ?? []
                        );
                        $this->response['success'] = true;

                        //$element = ElementFactory::createElement($element_info);
                    } else {
                        $this->response = [
                            'success' => false,
                            'errors' => $validation_result['user_error'],
                            'message' => 'Please fix the errors below.',
                        ];
                    }
                    break;
                case 'saveContactMessage':
                    $field_info = ModelSelector::getElementModel()
                        ->fetchFieldInfo(element_id: Utils::getRequestVar('element_id', true));

                    // Perform basic validation on contact fields, field inputs are retrieved internally
                    // $validaton_result will contain keys ['ok', 'user_error', 'field_inputs']
                    $validation_result = (new ValidationHandler)
                        ->validateFields(field_info: $field_info);

                    if ($validation_result['ok'] && isset($validation_result['field_inputs'])) {
                        $field_inputs = $validation_result['field_inputs'];
                        ModelSelector::getWebsiteInfoModel()->saveContact(
                            name: $field_inputs['name'],
                            email: $field_inputs['email'],
                            message: $field_inputs['message']
                        );

                        $this->response = [
                            'success' => true,
                            'errors' => $validation_result['user_error'] ?? [],
                            'message' => 'Your message has been sent.',
                        ];
                    } else {
                        $this->response = [
                            'success' => false,
                            'errors' => $validation_result['user_error'],
                            'message' => 'Please fix the errors below.',
                        ];
                    }
                    break;
                case 'registerUser':
                    $errors = [];

                    $field_info = ModelSelector::getElementModel()
                        ->fetchFieldInfo(
                            element_id: Utils::getRequestVar(
                                key: 'element_id',
                                frompost: True
                            )
                        );

                    $validation_result = (new ValidationHandler)
                        ->validateFields(field_info: $field_info);
                    $errors = array_merge($errors, $validation_result['user_error']);
                    if (!$validation_result['ok']) {
                        $this->response = [
                            'success' => False,
                            'errors' => $errors
                        ];
                        break;
                    }

                    $validation_result = UserHandler::getInstance()
                        ->handleRegistration(validation_result: $validation_result);
                    $errors = array_merge($errors, $validation_result['user_error']);
                    if (!$validation_result['ok']) {
                        $this->response = [
                            'success' => False,
                            'errors' => $errors
                        ];
                        break;
                    }

                    // All stages of validation succesful
                    $this->response = [
                        'page' => 'login',
                        'success' => True,
                        'message' => 'Registration was successful!'
                    ];
                    break;
                case 'loginUser':
                    $errors = [];

                    $field_info = ModelSelector::getElementModel()
                        ->fetchFieldInfo(
                            element_id: Utils::getRequestVar(
                                key: 'element_id',
                                frompost: True
                            )
                        );

                    $validation_result = (new ValidationHandler)
                        ->validateFields(field_info: $field_info);
                    $errors = array_merge($errors, $validation_result['user_error']);
                    if (!$validation_result['ok']) {
                        $this->response = [
                            'success' => False,
                            'errors' => $errors
                        ];
                        break;
                    }

                    $validation_result = UserHandler::getInstance()
                        ->handleUserLogin(validation_result: $validation_result);
                    $errors = array_merge($errors, $validation_result['user_error']);
                    if (!$validation_result['ok']) {
                        $this->response = [
                            'success' => False,
                            'errors' => $errors
                        ];
                        break;
                    }
                    $this->request['page'] = 'home';
                    $this->request['isLoggedIn'] = true;
                    $new_body_content = (new PageFactory($this->request))->buildBody();
                    $body_array = [
                        ['selector' => 'body', 'content' => $new_body_content, 'status' => 'pageHome']
                    ];
                    // All stages of validation succesful
                    $this->response = [
                        'page' => 'home',
                        'success' => True,
                        'message' => 'Login was successful!',
                        'html' => $body_array,
                    ];
                    break;
                case 'saveArticle':
                    $field_info = ModelSelector::getElementModel()
                        ->fetchFieldInfo(element_id: Utils::getRequestVar('element_id', true));

                    // Perform basic validation on contact fields, field inputs are retrieved internally
                    // $validaton_result will contain keys ['ok', 'user_error', 'field_inputs']
                    $validation_result = (new ValidationHandler)
                        ->validateFields(field_info: $field_info);

                    $this->response['bodyinfo']['title'] = $validation_result['field_inputs']['title'];
                    $this->response['bodyinfo']['bodytext'] = $validation_result['field_inputs']['bodytext'];
                    $this->response['bodyinfo']['codeBlock'] = $validation_result['field_inputs']['codeblock'];
                    $this->response['articletags'] = $validation_result['field_inputs']['articletags'];
                    $this->response['articleID'] = Utils::getRequestVar('articleID', true);


                    if ($validation_result['ok']) {
                        // This is the post request for editing or saving a (new) article

                        $validation_result = ArticleHandler::getInstance()
                            ->handleArticleSubmission(
                                validation_result: $validation_result,
                                article_id: $this->response['articleID'],
                                user_id: $this->request['userID']
                            );

                        if (isset($validation_result['field_inputs']['new_article_id'])) {
                            $this->response['articleID'] = $validation_result['field_inputs']['new_article_id'];
                        }

                        if ($validation_result['ok']) {
                            $this->response = [
                                'page' => 'article',
                                'success' => True,
                                'message' => 'Article has been submitted!'
                            ];
                        } else {
                            $this->response = [
                                'success' => false,
                                'errors' => $validation_result['user_error'],
                                'message' => 'Please fix the errors below.',
                            ];
                        }
                    } else {
                        $this->response = [
                            'success' => false,
                            'errors' => $validation_result['user_error'],
                            'message' => 'Please fix the errors below.',
                        ];
                    }
                    break;
                default:
                    $this->response = [
                        'success' => false,
                        'message' => 'Unknown AJAX action: ' . $this->request['action'],
                        'POST' => $this->request,
                    ];
            }
        } else {
            // GET requests
            switch ($this->request['action']) {
                case 'pageHome':
                case 'pageContact':
                case 'pageSearch':
                case 'pageRegister':
                case 'pageLogin':
                case 'pageDashboard':
                    $page = lcfirst(substr($this->request['action'], 4));
                    $this->request['page'] = $page;
                    $new_body_content = (new PageFactory($this->request))->buildBody();
                    $this->response = [
                        ['selector' => 'body', 'content' => $new_body_content, 'status' => $this->request['action']]
                    ];
                    break;
                case 'pageEditArticle':
                case 'pageArticle':
                    $page = lcfirst(substr($this->request['action'], 4));
                    $this->request['page'] = $page;
                    $this->request['articleID'] = utils::getRequestVar('articleID',false,0,true);
                    $new_body_content = (new PageFactory($this->request))->buildBody();
                    $this->response = [
                        ['selector' => 'body', 'content' => $new_body_content, 'status' => $this->request['action']]
                    ];
                    break;
                case 'pageAbout':
                    $this->request['aboutID'] = Utils::getRequestVar(
                        key: 'author',
                        frompost: false,
                        default: -1
                    );
                    $page = lcfirst(substr($this->request['action'], 4));
                    $this->request['page'] = $page;
                    $new_body_content = (new PageFactory($this->request))->buildBody();
                    $this->response = [
                        ['selector' => 'body', 'content' => $new_body_content, 'status' => $this->request['action']]
                    ];
                    break;
                case 'logout':
                    session_unset();
                    $this->request['page'] = 'home';
                    $this->request['isLoggedIn'] = false;
                    $new_body_content = (new PageFactory($this->request))->buildBody();
                    $this->response = [
                        ['selector' => 'body', 'content' => $new_body_content, 'status' => 'pageHome']
                    ];
                    break;
                default:
                    $this->response = [
                        'success' => false,
                        'message' => 'Unknown AJAX action: ' . $this->request['action'],
                        'POST' => $this->request,
                    ];
            }
        }
    }

    // turn $this->response into actual output — you're taking it from here
    private function showResponse(): void
    {
        // json stuff goes here?
        header("Content-type: application/json");
        echo json_encode($this->response);

        // future XML implementation
    }
}
