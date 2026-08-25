<?php

class PostRequestHandler extends BaseRequestHandler
{
    public function handle(array $request): array
    {
        $this->response = $request;
        switch ($request['page']) {
            case 'register':
                // new UserHandler
                // check email against database
                // check password against verifypassword
                // register user in database
                // move user to login screen OR automatically log in user?
                break;
            case 'login':
                // new UserHandler
                // check email against database
                // check password against database
                // set session['userID']
                break;
            case 'search':
                // collect checkboxgroup van tags en authors
                // collect sortby rating/datum
                // give collected checkboxes and sort to pagefactory
            case 'rateArticle':
                // This is actually an ajax function
                break;
            case 'newArticle':
                // href to editArticle without articleID=0

                break;
            case 'saveArticle':
                // check title against db
                // check user_id against db
                // if ok -> send article info to db
                break;
            case 'contact':
                $validator = new BaseValidator();
                UserHandler::getInstance()->checkContact($this->response, $validator);
                break;
        }

        $this->response['isLoggedIn'] = Utils::getSesVar('isLoggedIn', false);
        return $this->response;
    }

    public function createPage(): BasePage
    {
        
        return (new PageFactory($this->response))->show();
    }
}
