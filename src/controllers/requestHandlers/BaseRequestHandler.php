<?php

abstract class BaseRequestHandler implements iRequestHandler
{
    protected array $response;

    abstract public function handleRequest(array $request): array;
}