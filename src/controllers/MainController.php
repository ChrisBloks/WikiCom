<?php
namespace Wiki\controllers;
use Wiki\tools\interfaces;

/**
 * Controller class for initial request handling and forwarding request to the correct subcontroller.
 * @var bool $async true if MainController is accessed through an AJAX request, otherwise false.
 */
class MainController
{
    protected bool $posted;
    protected bool $async;
    protected string $action;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->async = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    /**
     * Get the relevant subcontroller and have it handle the incoming request
     * @return void
     */
    public function main(): void
    {
        $this->getHandler()->handleRequest();
    }

    /**
     * Get the relevant subcontroller based on the request. Default is PageController.
     * @return interfaces\iController
     */
    private function getHandler(): interfaces\iController
    {   
        // Ajax request. Action is equal to a specific update
        if($this->async){
            // return new AjaxController();
        }

        // Default
        // Non-ajax request. Action is equal to the requested page
        return new PageController();
    }
}
