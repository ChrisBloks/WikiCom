<?php

abstract class BaseRequestHandler implements iRequestHandler
{
    protected array $response;

    abstract public function handle(array $request): array;
    abstract public function createPage(): BasePage;
}