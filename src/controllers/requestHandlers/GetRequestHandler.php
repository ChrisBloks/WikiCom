<?php

namespace Wiki\controllers\requestHandlers;

use Wiki\tools\utils\Utils;

class GetRequestHandler extends BaseRequestHandler
{
    public function handleRequest(array $request): array
    {

        $this->response = $request;

        switch ($request['page']) {
            case 'logout':
                $this->response['page'] = 'home';
                $this->response['isLoggedIn'] = false;
                session_unset();
                break;
            case 'article':
                $this->response['articleID'] = Utils::getRequestVar('id', false);
                break;
            case 'editArticle':
                $this->response['editArticleID'] = Utils::getRequestVar('id', false);
                break;
            CASE 'dashboard':
                break;
            case 'about';
                $this->response['aboutID'] = Utils::getRequestVar('author', false);
                $this->response['userID'] = Utils::getSesVar('userID');
                break;
        }

        return $this->response;
    }
}
