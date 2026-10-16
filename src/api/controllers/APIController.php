<?php

namespace Wiki\api\controllers;

use HTMLPurifier_UnitConverter;
use Wiki\api\handlers\APIHandler;
use Wiki\tools\interfaces\iController;
use Wiki\tools\utils\HtmlUtils;
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
            //$this->showResponse();
        } catch (\Exception) {

        }
    }

    private function getRequest()
    {
        if ($this->posted) {
            $this->request = json_decode(file_get_contents('php://input'), true); 
        }
        // $this->request = [
        //     'action' => Utils::getRequestVar(
        //         key: 'action',
        //         frompost: false,
        //         default: ''
        //     ),
        // ];
    }

    private function validateRequest()
    {
        (new APIHandler)->handleRequest($this->request);
    }

    // private function showResponse()
    // {
    //     // switch statement later that can return html, json, or xml
    //     header("Content-type: application/json");
    //     echo json_encode($this->response);
    // }
}
