<?php

namespace Wiki\system\handlers;

use Wiki\tools\interfaces\iRequestHandler;

abstract class BaseRequestHandler implements iRequestHandler
{
    protected array $response;

}
