<?php

namespace Wiki\ajax\handlers;

use Wiki\models\ModelSelector;
use Wiki\system\factories\PageFactory;
use Wiki\system\handlers\ArticleHandler;
use Wiki\system\handlers\BaseRequestHandler;
use Wiki\system\handlers\UserHandler;
use Wiki\system\handlers\ValidationHandler;
use Wiki\tools\utils\Utils;

class PostRequestHandler extends BaseRequestHandler
{
    // TODO: add documentation
    // TODO: add validationHandler as dependency injection?
    public function handleRequest(array $request): array
    {
        switch ($request['action']) {
            case 'saveRating':
                if (!$request['isLoggedIn']) {
                    throw new \BadMethodCallException('Tried to save a rating without being logged in!');
                } else {
                    $rating = Utils::getRequestVar('rating', true, null);
                    $article_id = utils::getRequestVar('article_id', true, null);
                    $articleHandler = new ArticleHandler();
                    $rating_info = $articleHandler->handleSaveRating(
                        user_id: $request['userID'],
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
                    article_id: (int) $request['id'],
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
                        'userID' => $request['userID'] ?? null,
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
                $request['page'] = 'home';
                $request['isLoggedIn'] = true;
                $new_body_content = (new PageFactory($request))->buildBody();
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
                            user_id: $request['userID']
                        );

                    if (isset($validation_result['field_inputs']['new_article_id'])) {
                        $this->response['articleID'] = $validation_result['field_inputs']['new_article_id'];
                    }

                    if ($validation_result['ok']) {
                        $request['page'] = 'article';
                        $request['articleID'] = $this->response['articleID'];
                        $new_body_content = (new PageFactory($request))->buildBody();
                        $body_array = [
                            ['selector' => 'body', 'content' => $new_body_content, 'status' => 'pageHome']
                        ];
                        // All stages of validation succesful
                        $this->response = [
                            'page' => 'article',
                            'success' => True,
                            'message' => 'Article was successfully saved!',
                            'html' => $body_array,
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
                    'message' => 'Unknown AJAX action: ' . $request['action'],
                    'POST' => $request,
                ];


        }
        return $this->response;
    }
}
