<?php

class GetRequestHandler extends BaseRequestHandler
{
    public function handle(array $request): array {
        
        $this->response = $request;

        switch($request['page']){
            case 'logout':
                $this->response['page'] = 'home';
                // UserHandler->logoutUser();
                break;
            case 'Article':
            case 'editArticle':
            case 'About';
                // getRequestVar['id']
                // $this->response['id'] = getRequestVar['id']
        }
        
        $this->response['isLoggedIn'] = Utils::getSesVar('isLoggedIn', false);
        return $this->response;
    }

    public function createPage(): BasePage{
        
        return (new PageFactory($this->response))->show();
    }
}