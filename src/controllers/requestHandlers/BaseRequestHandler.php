<?php

namespace Wiki\controllers\requestHandlers;

use Wiki\tools\interfaces\iRequestHandler;

abstract class BaseRequestHandler implements iRequestHandler
{
    protected array $response;

    abstract public function handleRequest(array $request): array;
}
