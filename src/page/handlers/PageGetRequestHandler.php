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

        if (in_array($this->response['page'], ModelSelector::getWebsiteInfoModel()->getValidPages($this->response['isLoggedIn']))) {

            switch ($request['page']) {
                case 'article':
                    $this->response['articleID'] = Utils::getRequestVar('articleID', false);
                    break;
                case 'editArticle':
                    $this->response['articleID'] = Utils::getRequestVar('articleID', false);
                    if (empty($this->response['articleID'])) {
                        $_SESSION['errors'][] = 'Invalid article ID!';
                        $this->response['page'] = 'home';
                        break;
                    }
                    $author_id = ModelSelector::getArticleModel()->fetchAuthor($this->response['articleID']);
                    if ($this->response['userID'] != $author_id) {
                        $_SESSION['errors'][] = '403 Forbidden';
                        $this->response['page'] = 'home';
                    }
                    break;
                case 'dashboard':
                    if (!$this->response['isLoggedIn']) {
                        $_SESSION['errors'][] = '403 Forbidden';
                        $this->response['page'] = 'home';
                    }
                    break;
                default:
                    // $this->response['page'] = 'home';
                    break;

            }

        } else {
            $this->response['page'] = 'Error';
        }

        return $this->response;

    }


}
