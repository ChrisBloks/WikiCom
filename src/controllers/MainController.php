<?php
require_once "./src/controllers/PageController.php";

/**
 * Controller class for initial request handling and forwarding request to the correct subcontroller.
 */
class MainController
{
<<<<<<< HEAD

    public function __construct()
    {
        // Add something
=======
    protected bool $posted;
    protected bool $async;
    protected string $action;     

    public function __construct()
    {
        $this -> posted = ($_SERVER['REQUEST_METHOD']=== 'POST');
        $this -> async = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
        //$this -> action
>>>>>>> 68de54da0575fdc0e0184c6e54bbf66a021806fe
    }

    // start new controller if request is of AJAX type
    public function main(): void
    {
        $this->resolveHandler()->handleRequest();
    }

    // if more request types are added, add them to the if-else loop
    private function resolveHandler() 
    {
<<<<<<< HEAD
        if ($this->isAjaxRequest()) {
            // return new AjaxController($this->_crud);
        }
=======
        // switch ($this->action)
        // case ajax: {
        //     return new AjaxController($this->_crud);
        //     break;
        // }
>>>>>>> 68de54da0575fdc0e0184c6e54bbf66a021806fe

        return new PageController($this -> posted);
    }
}