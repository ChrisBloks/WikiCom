<?php

namespace Wiki\api\actions;
use Wiki\tools\interfaces\iAPIfunction;
use Wiki\tools\utils\HtmlUtils;


abstract class BaseAsyncAction implements iAPIFunction
{
    protected mixed $data;
//==============================================================================
    final public function execute() : bool
    {
        if ($this->_getData())
        {
            $this->_sendData();
        } 
        return false;
    }    
//==============================================================================
    abstract protected function _getData() : bool;
//==============================================================================
    abstract protected function _sendData() : bool;
}