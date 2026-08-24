<?php
require_once "./src/controllers/PageController.php";

/**
 * Controller class for initial request handling and forwarding request to the correct subcontroller.
 */
class MainController
{

    public function __construct()
    {
        // Add something
    }

    // start new controller if request is of AJAX type
    public function main(): void
    {
        $this->resolveHandler()->handleRequest();
    }

    // if more request types are added, add them to the if-else loop
    private function resolveHandler() 
    {
        if ($this->isAjaxRequest()) {
            // return new AjaxController($this->_crud);
        }

        return new PageController();
    }

    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}