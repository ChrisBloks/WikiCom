<?php
namespace Wiki\tools\interfaces;

interface iRequestHandler{
    public function handle(array $request): array;
    public function createPage(): BasePage;
}