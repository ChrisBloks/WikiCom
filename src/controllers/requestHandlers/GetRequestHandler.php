<?php

namespace Wiki\controllers\requestHandlers;

use Wiki\models\ModelSelector;
use Wiki\tools\utils\HtmlUtils;
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
                $this->response['userID'] = Utils::getSesVar('userID');
                $this->response['editArticleID'] = Utils::getRequestVar('id', false);
                if (empty($this->response['editArticleID'])) {
                    $this->response['editArticleID']  = 0;
                }
                else{
                    $article_info = ModelSelector::getArticleModel()->fetchArticleByID($this->response['editArticleID']);
                    if ($this->response['userID'] != $article_info['user_id']) {
                        $this->response['page'] = 'article';
                        $this->response['articleID']  = $this->response['editArticleID'];
                        $this->response['user_error'][] = "You are not authorized to edit this article.";
                    }
                }


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
