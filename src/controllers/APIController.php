<?php

namespace Wiki\controllers;

use Wiki\tools\interfaces\iController;

class APIController implements iController
{
    
    public function handleRequest(){
    
        $this->getRequest();
        $this->validateRequest();
        $this->showResponse();
    }

    private function getRequest(){

    }

    private function validateRequest(){

    }
    private function showResponse(){

    }
}