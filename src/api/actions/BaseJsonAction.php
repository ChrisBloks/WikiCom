<?php

namespace Wiki\api\actions;

use Wiki\tools\traits\tErrorMessageCollector;
abstract class BaseJsonAction extends BaseAsyncAction
{
    use tErrorMessageCollector;
    //==============================================================================
    final protected function _sendData() : bool
    {
        if ($this->_JSONEncode())
        {    
            header("Content-type: application/json");
            echo $this->data;
            return true;
        }
        return false;
    }        
//==============================================================================
    protected function _JSONEncode() : bool
    {
        $this->data = json_encode($this->data);
        if ($this->data === false)
        {	
            $msg = "JSON PARSE ERROR - ";
            switch (json_last_error()) 
            {
                case JSON_ERROR_NONE:
                    $msg .= 'No errors';
                    break;
                case JSON_ERROR_DEPTH:
                    $msg .= 'Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $msg .= 'Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $msg .= 'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $msg .= 'Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $msg .= 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $msg .= 'Unknown error';
                    break;
            }			
            $this->logError("JSON RAW DATA ", $this->data);
            throw new \Exception($msg);
        }
        return true;
    }    
//==============================================================================
            
}
