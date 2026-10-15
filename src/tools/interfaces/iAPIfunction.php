<?php

namespace Wiki\tools\interfaces;

interface iAPIfunction
{
    public function _getData() : bool;

    public function _sendData() : bool;
}