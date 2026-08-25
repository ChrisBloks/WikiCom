<?php

class ValidateRequest
{
    protected array $response;
    public function __construct(array $response)
    {
        $this->response = $response;
        $this->validateRequest();
    }

    protected function validateRequest()
    {
        if ($this->response['posted']) {

            // validator nodig

            switch ($this->response['page']) {

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
                    $validator = new ContactValidator();
                    UserHandler::getInstance() -> checkContact($this->response,$validator);
                    break;
            }
        } else {
            switch ($this->response['page']) {
                case 'logout':
                    // UserHandler->logoutUser();
                    break;
                case 'Article':
                case 'editArticle':
                case 'About';
                    // getRequestVar['id']
                    // $this->response['id'] = getRequestVar['id']
            }

        }


    }
    public function show()
    {
        return $this -> response;
    }
}