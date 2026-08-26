<?php

namespace Wiki\controllers\requestHandlers;

use Wiki\tools\utils\Utils,
    Wiki\tools\utils\HtmlUtils,
    Wiki\controllers\validators\BaseValidator,
    Wiki\controllers\validators\RegisterValidator,
    Wiki\controllers\UserHandler,
    Wiki\controllers\validators\Validator,
    Wiki\models\ModelSelector;

class PostRequestHandler extends BaseRequestHandler
{
    public function handleRequest(array $request): array
    {
        $this->response = $request;
        switch ($request['page']) {
            case 'register':
                // Validate user inputs on the registration form
                $validator = new RegisterValidator();
                $userInfo = UserHandler::getInstance()->checkRegistration($request, $validator);
                
                // If validation was succesful, add new user to the database
                if ($userInfo !== false){
                    $registrationResult = ModelSelector::getUserInfoModel()
                        ->saveUser(
                            username: $userInfo['name'],
                            password: $userInfo['password'],
                            email: $userInfo['email']
                        );
                }
                break;
            case 'login':
                // Validate user inputs and on succes: get logged-in user's info
                $validator = new Validator();
                $userinfo = UserHandler::getInstance()->checkLogin($this->response, $validator);

                // If log in was succesful
                if ($userinfo !== false) {
                    // Update session variables
                    $_SESSION['userName'] = $userinfo['name'];
                    $_SESSION['userID'] = $userinfo['id'];

                    // Update response
                    $this->response['page'] = 'home';
                    $this->response['isLoggedIn'] = true;
                }

                break;
            case 'about':
                $this->response['aboutID'] = Utils::getRequestVar('author', false);
                $this->response['userID'] = Utils::getSesVar('userID');
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
                $contactInfo = UserHandler::getInstance()->checkContact($request, $validator);
                
                // On succesful contact form validaiton, save input to the database
                if ($contactInfo !== false){
                    ModelSelector::getWebsiteInfoModel()->saveContact(
                        name: $contactInfo['name'],
                        email: $contactInfo['email'],
                        message: $contactInfo['message']
                    );
                }
                break;
        }

        return $this->response;
    }
}
