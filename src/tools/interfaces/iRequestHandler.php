<?php

interface iRequestHandler{
    public function handleRequest(array $response): array;
}