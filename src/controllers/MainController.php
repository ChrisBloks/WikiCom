<?php


class MainController extends BaseController
{
    protected bool $async;
    protected iController $handler;

    public function handleRequest()
    {   
        $this->async = parent::$async;
        $this->chooseHandler();
        $this -> handler-> handleRequest();
    }

    public function chooseHandler()
    {   
        if ($this -> async) {
            $this -> handler = new AjaxController();
        } else {
            $this -> handler = new PageController();
        }
    }


}