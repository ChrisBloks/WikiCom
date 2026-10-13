<?php

namespace Wiki\controllers\requestHandlers;

use Wiki\models\ModelSelector;
use Wiki\tools\utils\HtmlUtils;
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
