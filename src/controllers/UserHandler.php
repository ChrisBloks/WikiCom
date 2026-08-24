<?php

class UserHandler
{
    public function checkLogin(&$response, $validator)
    {
        $form_fields = ModelSelector::getFormModel()->fetchFormFields($response['page']);
        $validator = new FormValidator($response,$form_fields);
        $result = $validator->validateLogin($response, $form_fields);
        if ($result['ok']) {
            $response['page'] = 'home';
            $_SESSION['username'] = $result['name'];
            $_SESSION['userID'] = $result['id'];
        }
    }

    public function checkRegistration(&$response, $validator)
    {
        $form_fields = ModelSelector::getFormModel()->fetchFormFields($response['page']);
        $result = $validator->validateLogin($response, $form_fields);
        if ($result['ok']) {
            $response['page'] = 'login';
            ModelSelector::getUserInfoModel()->registerUser(
                                                            username:$result['username'],
                                                            password:$result['password'],
                                                            email:$result['email'],
                                                            imgFileName:$result['imgFileName'],
                                                            description:$result['description']
            );
        }
    }

    public function checkContact(&$response, $validator)
    {
        $form_fields = ModelSelector::getFormModel()->fetchFormFields($response['page']);
        $result = $validator->validateLogin($response, $form_fields);
        if ($result['ok']) {
            ModelSelector::getWebsiteInfoModel()->saveContact(
                                                              name:$result['name'],
                                                              email:$result['email'],
                                                              message:$result['message']
            );
        }
    }

}