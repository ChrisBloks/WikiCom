<?php
namespace Wiki\controllers;

use BadMethodCallException;
use Wiki\tools\interfaces\iController;
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
                if(!$this->request['isLoggedIn']){
                    throw new BadMethodCallException('Tried to save a rating without being logged in!');
                }
                else {
                    HtmlUtils::dump("session", $_SESSION);
                    HtmlUtils::dump("request", $this->request);
                    $rating = utils::getRequestVar('rating', true, null);
                    $article_id = utils::getRequestVar('article_id', true, null);
                    $articleHandler = new ArticleHandler();
                    $new_avg_rating = $articleHandler->handleSaveRating(
                        user_id: $this->request['user_id'],
                        article_id: $article_id,
                        rating: $rating);
                    $this->response = [
                        'avg_rating' => $new_avg_rating
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