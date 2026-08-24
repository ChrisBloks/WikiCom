<?php
require_once "./src/controllers/PageController.php";

/**
 * Controller class for initial request handling and forwarding request to the correct subcontroller.
 * @var bool $posted true if MainController is accessed through a POST request, otherwise false.
 * @var bool $async true if MainController is accessed through an AJAX request, otherwise false.
 * @var string $action determines which (sub)controller should resolve the request.
 */
class MainController
{
    protected bool $posted;
    protected bool $async;
    protected string $action;

    /**
     * __construct
     * @return void
     */
    public function __construct()
    {
        $this->posted = ($_SERVER['REQUEST_METHOD'] === 'POST');
        $this->async = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $this->action = Utils::getRequestVar(
            key: 'action',
            frompost: $this->posted,
            default: $this->posted ? '' : 'home'
        );
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
     * @return iController
     */
    private function getHandler(): iController
    {   
        // Ajax request. Action is equal to a specific update
        if($this->async){
            switch ($this->action) {
                        case 'blah':
                            // return new AjaxController($this->_crud);
                            // break;
                        default:
                            // TODO: overwrite
                            return new PageController($this->posted, $this->action);
                    }
        }
        // Non-ajax request. Action is equal to the requested page
        else {
            return new PageController($this->posted, $this->action);
        }
        
    }
}
