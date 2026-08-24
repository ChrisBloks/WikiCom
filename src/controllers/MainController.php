<?php
require_once "./src/controllers/PageController.php";

class MainController
{
    protected bool $posted;
    protected bool $async;
    protected string $action;     

    public function __construct()
    {
        $this -> posted = ($_SERVER['REQUEST_METHOD']=== 'POST');
        $this -> async = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
        //$this -> action
    }

    // start new controller if request is of AJAX type
    public function main(): void
    {
        $this->resolveHandler()->handleRequest();
    }

    // if more request types are added, add them to the if-else loop
    private function resolveHandler() 
    {
        // switch ($this->action)
        // case ajax: {
        //     return new AjaxController($this->_crud);
        //     break;
        // }

        return new PageController($this -> posted);
    }
}