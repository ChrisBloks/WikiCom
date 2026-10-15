<?php

namespace Wiki\api\controllers;

use Wiki\api\handlers\NewApiHandler,
    Wiki\tools\interfaces\iController,
    Wiki\tools\utils\Utils;

class APIController implements iController
{
    private bool $posted;
    private array $request;
    private array $response;

    public function __construct()
    {
    }

    public function handleRequest()
    {
        try {
            $this->getRequest();
            $this->validateRequest();
            $this->showResponse();
        } catch (\Exception) {
            http_response_code(500);
            header("Content-type: application/json");
            echo json_encode(['error' => 'Internal server error']);
        }
    }

    private function getRequest()
    {
        $this->request = [
            'action' => Utils::getRequestVar(
                key: 'action',
                frompost: false,
                default: ''
            ),
        ];
    }

    private function validateRequest()
    {
        $this->response = (new NewApiHandler)->handleRequest();
    }

    private function showResponse()
    {
        // switch statement later that can return html, json, or xml
        header("Content-type: application/json");
        echo json_encode($this->response);
    }
}
