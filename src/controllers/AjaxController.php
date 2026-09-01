<?php
namespace Wiki\controllers;


use Wiki\tools\interfaces\iController;
use Wiki\tools\utils\utils;

class AjaxController implements iController{

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
            'func' => utils::getRequestVar('func', true, 'unknown'),

        ];
    }

    // decide what to do based on the action, fill $this->response
    private function validateRequest(): void
    {
        switch ($this->request['func']) {
            case 'saveRating':
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