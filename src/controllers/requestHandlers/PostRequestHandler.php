<?php

class PostRequestHandler extends BaseRequestHandler
{
    public function handleRequest(array $request): array
    {
        $this -> response = $request;
        switch ($request['page']) {
            case 'register':
                $validator = new RegisterValidator();
                UserHandler::getInstance()->checkRegistration($request, $validator);
                break;
            case 'login':
                $validator = new BaseValidator();
                $userinfo = UserHandler::getInstance()->checkLogin($request, $validator);
                $this -> response['page'] = 'home';
                $_SESSION['name'] = $userinfo['name'];
                $_SESSION['userID'] = $userinfo['id'];  
                $this -> response['isLoggedIn'] = isset($_SESSION['userID']);
                break;
            case 'search':
            // collect checkboxgroup van tags en authors
            // collect sortby rating/datum
            // give collected checkboxes and sort to pagefactory
            case 'rateArticle':
                // This is actually an ajax function
                break;
            case 'dashboard':
                $this->response['page'] = 'editArticle';
                $this->response['editArticleID'] = 0;
                break;
            case 'editArticle':
                // add validator
                print_r($_POST);
                $this->response['editArticleID'] = 0;
                // check user_id against db
                // if ok -> send article info to db
                break;
            case 'contact':
                $validator = new BaseValidator();
                UserHandler::getInstance()->checkContact($request, $validator);
                break;
        }

        return $this -> response;
    }

}
