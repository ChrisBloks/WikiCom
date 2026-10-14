<?php

namespace Wiki\controllers;

use Wiki\tools\interfaces\iController,
Wiki\tools\utils\Utils,
Wiki\tools\utils\HtmlUtils;

class APIController implements iController
{
    private bool $posted;
    private array $request;
    private array $response;
    
    public function handleRequest()
    {
        $this->getRequest();
        $this->validateRequest();
        $this->showResponse();
    }

    private function getRequest()
    {
        $this->request = [
            'action' => Utils::getRequestVar(
                key: 'action',
                frompost: $this->posted,
                default: 'home'
            ),
        ];
    }

    private function validateRequest()
    {
        switch ($this->request['action']) {

            case 'getAllUsers':
                $this->response = (new APIhandler)->handleGetAllUsers();
                break;
            case 'getAllArticles':
                $this->response = (new APIhandler)->handleGetAllArticles();
                break;
            case 'getAllTags':
                $this->response = (new APIhandler)->handleGetAllTags();
                break;
            case 'getArticlesByUser':
                $this->request['userID'] = Utils::getRequestVar('userID', false, 0, true);
                $this->response = (new APIhandler)->getArticlesByUserID($this->request['userID']);
                break;
            case 'getArticlesByTag':
                $this->request['tag'] = Utils::getRequestVar('tag', false, 0, true);
                $this->response = (new APIhandler)->getArticlesByTag($this->request['tag']);
                break;
            case 'getArticlesByUserTag':
                $this->request['tag'] = Utils::getRequestVar('tag', false, 0, true);
                $this->request['userID'] = Utils::getRequestVar('userID', false, 0, true);
                $this->response = (new APIhandler)->getArticlesByUserAndTag($this->request['tag'], $this->request['userID']);
                break;
            case 'getArticleById':
                $this->request['articleID'] = Utils::getRequestVar('articleID', false, 0, true);

                break;
        }
    }
    private function showResponse()
    {
        // switch statement later that can return html, json, or xml
        header("Content-type: application/json");
        echo json_encode($this->response);
    }
}