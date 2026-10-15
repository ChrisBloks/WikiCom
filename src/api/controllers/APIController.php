<?php

namespace Wiki\api\controllers;

use Wiki\api\Handlers\APIHandler;
use Wiki\tools\interfaces\iController;
use Wiki\tools\utils\Utils;

class APIController implements iController
{
    private bool $posted;
    private array $request;
    private array $response;

    public function __construct()
    {
        $this->posted = ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    public function handleRequest()
    {
        try {
        $this->getRequest();
        $this->validateRequest();
        $this->showResponse();
        }
        catch (\Exception){

        }
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
        $this->response = (new APIHandler)->handleRequest($this->request);
    }
    
    private function showResponse()
    {
        // switch statement later that can return html, json, or xml
        header("Content-type: application/json");
        echo json_encode($this->response);
    }
}