<?php


class MainController extends BaseController
{
    protected bool $action;
    protected iController $handler;

    public function handleRequest() : void
    {   
        $this->action = parent::$async;
        $this->chooseHandler();
        $this -> handler-> handleRequest();
    }

    public function chooseHandler()
    {   
        if ($this -> action) {
            $this -> handler = new AjaxController();
        } else {
            $this -> handler = new PageController();
        }
    }


}