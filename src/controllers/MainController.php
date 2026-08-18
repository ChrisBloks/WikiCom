<?php


class MainController
{
    protected array $request;
    protected array $response;
    protected bool $async;
    protected iController $handler;

    public function __construct()
    {
        $this -> async =  isset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    public function chooseHandler()
    {   
        if ($this -> async) {
            $this -> handler = new AjaxController();
        } else {
            $this -> handler = new PageController();
        }
    }

    public function handleRequest()
    {   
        $this->chooseHandler();
        $this -> handler-> handleRequest();
    }
}