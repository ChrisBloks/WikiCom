<?php

namespace Wiki\controllers\requestHandlers;

use Wiki\tools\interfaces\iRequestHandler;

abstract class BaseGetRequestHandler extends BaseRequestHandler implements iRequestHandler 
{
    protected array $response;

    abstract function handleRequest(array $request): array;

}
