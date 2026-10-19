<?php

namespace Wiki\page\handlers;

use Wiki\models\ModelSelector;
use Wiki\system\handlers\BaseRequestHandler;
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
                $this->response['articleID'] = Utils::getRequestVar('articleID', false);
                break;
            case 'editArticle':
                HtmlUtils::dump('response', $this->response);
                $this->response['articleID'] = Utils::getRequestVar('articleID', false);
                $author_id = ModelSelector::getArticleModel()->fetchAuthor($this->response['articleID']);
                if($this->response['userID'] != $author_id){
                    $_SESSION['errors'][] = '403 Forbidden';
                    $this->response['page'] = 'home';
                }
                break;
            case 'dashboard':
                HtmlUtils::dump('response', $this->response);
                if(!$this->response['isLoggedIn']){
                    $_SESSION['errors'][] = '403 Forbidden';
                    $this->response['page'] = 'home';
                }
                break;
            default:
                // $this->response['page'] = 'home';
                break;
        }

        
        return $this->response;
    }


}
