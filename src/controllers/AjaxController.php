<?php
namespace Wiki\controllers;

use BadMethodCallException;
use Wiki\tools\interfaces\iController,
Wiki\models\ModelSelector;
use Wiki\tools\utils\utils;
use Wiki\controllers\ArticleHandler;
use Wiki\tools\utils\HtmlUtils;

class AjaxController implements iController
{

    private array $request;
    private array $response;


    public function __construct()
    {
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
            'page' => utils::getRequestVar('page', true, 'home'),
            'action' => utils::getRequestVar('action', true, 'unknown'),
            'id' => utils::getRequestVar('id', true, null),
            'user_id' => utils::getSesVar('userID', null),
            'isLoggedIn' => isset($_SESSION['userID'])
        ];
    }

    // decide what to do based on the action, fill $this->request
    private function validateRequest(): void
    {
        switch ($this->request['action']) {
            case 'saveRating':
                if (!$this->request['isLoggedIn']) {
                    throw new BadMethodCallException('Tried to save a rating without being logged in!');
                } else {
                    $rating = utils::getRequestVar('rating', true, null);
                    $article_id = utils::getRequestVar('article_id', true, null);
                    $articleHandler = new ArticleHandler();
                    $rating_info = $articleHandler->handleSaveRating(
                        user_id: $this->request['user_id'],
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

                $field_info = ModelSelector::getFormModel()
                    ->fetchFieldInfo(page_name: $this->request['page']);

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
                        'user_id' =>$this->request['user_id']?? null,
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

                $field_info = ModelSelector::getFormModel()
                    ->fetchFieldInfo(page_name: $this->request['page']);

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
            default:
                $this->response = [
                    'success' => false,
                    'message' => 'Unknown AJAX action: ' . $this->request['action'],
                ];
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