<?php
namespace Wiki\tools\interfaces;

interface iRequestHandler{
    public function handleRequest(array $response): array;
}