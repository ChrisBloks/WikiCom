<?php

interface iRequestHandler{
    public function handle(array $request): array;
    public function createPage(): BasePage;
}