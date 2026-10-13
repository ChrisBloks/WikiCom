<?php

namespace Wiki\controllers;

use 
Wiki\tools\interfaces\iController,
Wiki\controllers\requestHandlers\PostRequestHandler,
Wiki\tools\utils\utils,
Wiki\controllers\ArticleHandler,
Wiki\controllers\factories\PageFactory,
Wiki\tools\utils\HtmlUtils;

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
            $this->response = (new PostRequestHandler)->handleRequest($this->request);
        } else {
            // GET requests
            switch ($this->request['action']) {
                case 'pageHome':
                case 'pageContact':
                case 'pageSearch':
                case 'pageRegister':
                case 'pageLogin':
                case 'pageDashboard':
                case 'pageEditArticle':
                case 'pageArticle':
                    $page = lcfirst(substr($this->request['action'], 4));
                    $this->request['page'] = $page;
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
