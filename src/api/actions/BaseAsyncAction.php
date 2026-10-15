<?php

namespace Wiki\api\actions;
use Wiki\tools\interfaces\iAPIfunction;

abstract class BaseAsyncAction implements iAPIFunction
{
    protected mixed $data;
//==============================================================================
    final public function execute() : bool
    {
        if ($this->_getData())
        {
            return $this->_sendData();
        } 
        return false;
    }    
//==============================================================================
    abstract protected function _getData() : bool;
//==============================================================================
    abstract protected function _sendData() : bool;
}