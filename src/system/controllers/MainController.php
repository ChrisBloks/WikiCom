<?php

namespace Wiki\system\controllers;

use Wiki\ajax\controllers\AjaxController;
use Wiki\api\controllers\APIController;
use Wiki\page\controllers\PageController;
use Wiki\tools\interfaces;
use Wiki\tools\utils\HtmlUtils;
use Wiki\tools\utils\Utils;

/**
 * Controller class for initial request handling and forwarding request to the correct subcontroller.
 */
class MainController
{
    protected bool $posted;
    protected array $request;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->request = [
            'reqType' => Utils::getRequestVar(
                key: 'reqType',
                frompost: false,
                default: 'home'
            ),
        ];

        //Page tracking
        // if (!isset($_SESSION['currentPage'])){$_SESSION['currentPage'] = 'home';}
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
        switch ($this->request['reqType']) {
            case 'ajax':
                return new AjaxController();
            case 'API':
                return new APIController();
            default:
                return new PageController();
        }
    }
}
