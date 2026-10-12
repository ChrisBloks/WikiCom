<?php

namespace Wiki\controllers;

use Wiki\tools\interfaces;
use Wiki\tools\utils\Utils;
use Wiki\controllers\AjaxController;

/**
 * Controller class for initial request handling and forwarding request to the correct subcontroller.
 * @var bool $async true if MainController is accessed through an AJAX request, otherwise false.
 */
class MainController
{
    protected bool $posted;
    protected array $request;
    protected bool $async;
    protected string $action;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->request = [
            'posted' => $this->posted,
            'reqType' => Utils::getRequestVar(
                key: 'reqType',
                frompost: $this->posted,
                default: ($this->posted ? '' : 'home'
                )
            ),
        ];
    }

    /**
     * Get the relevant subcontroller and have it handle the incoming request
     * @return void
     */
    public function main(): void
    {
        $this->getHandler()->handleRequest();
    }

    /**
     * Get the relevant subcontroller based on the request. Default is PageController.
     * @return interfaces\iController
     */
    private function getHandler(): interfaces\iController
    {
        switch ($this->request['reqtype']) {
            case 'ajax':
                return new AjaxController();
            default:
                return new PageController();
        }
    }
}
