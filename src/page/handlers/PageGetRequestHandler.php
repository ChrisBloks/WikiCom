<?php

namespace Wiki\page\handlers;

use Wiki\system\handlers\BaseRequestHandler;
use Wiki\tools\utils\Utils;

class PageGetRequestHandler extends BaseRequestHandler
{
    public function handleRequest(array $request): array
    {
        $_SESSION['errors'] = [];
        $this->response = $request;

        switch ($request['page']) {
            case 'article':
                $this->response['articleID'] = Utils::getRequestVar('id', false);
            default:
                // $this->response['page'] = 'home';
                break;
        }

        
        return $this->response;
    }


}
