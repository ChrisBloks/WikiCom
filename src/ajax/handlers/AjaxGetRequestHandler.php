<?php

namespace Wiki\ajax\handlers;

use Wiki\system\factories\PageFactory,
Wiki\tools\utils\Utils;
use Wiki\system\handlers\BaseGetRequestHandler;

class AjaxGetRequestHandler extends BaseGetRequestHandler
{
    // properties
    protected array $request;

    public function handleRequest(array $request): array
    {
        $this->request = $request;

        switch ($this->request['action']) {
            case 'pageHome':
            case 'pageContact':
            case 'pageSearch':
            case 'pageRegister':
            case 'pageLogin':
            case 'pageDashboard':
                $page = lcfirst(substr($this->request['action'], 4));
                $this->request['page'] = $page;
                $new_body_content = (new PageFactory($this->request))->buildBody();
                $this->response = [
                    ['selector' => 'body', 'content' => $new_body_content, 'status' => $this->request['action']]
                ];
                break;
            case 'pageEditArticle':
            case 'pageArticle':
                $page = lcfirst(substr($this->request['action'], 4));
                $this->request['page'] = $page;
                $this->request['articleID'] = Utils::getRequestVar('articleID',false,0,true);
                $new_body_content = (new PageFactory($this->request))->buildBody();
                $this->response = [
                    ['selector' => 'body', 'content' => $new_body_content, 'status' => $this->request['action']]
                ];
                break;
            case 'pageAbout':
                $this->request['aboutID'] = Utils::getRequestVar(
                    key: 'author',
                    frompost: false,
                    default: -1
                );
                $page = lcfirst(substr($this->request['action'], 4));
                $this->request['page'] = $page;
                $new_body_content = (new PageFactory($this->request))->buildBody();
                $this->response = [
                    ['selector' => 'body', 'content' => $new_body_content, 'status' => $this->request['action']]
                ];
                break;
            case 'logout':
                session_unset();
                $this->request['page'] = 'home';
                $this->request['isLoggedIn'] = false;
                $new_body_content = (new PageFactory($this->request))->buildBody();
                $this->response = [
                    ['selector' => 'body', 'content' => $new_body_content, 'status' => 'pageHome']
                ];
                break;
            default:
                $this->response = [
                    'success' => false,
                    'message' => 'Unknown AJAX action: ' . $this->request['action'],
                    'POST' => $this->request,
                ];
                break;
        }
        return $this->response;
    }
}
