<?php

class UserHandler
{
    use tSingleton;
    public function checkLogin(&$response, $validator)
    {
        $form_fields = ModelSelector::getFormModel()->fetchFormFields($response['page']);
        $validator = new Validator($response, $form_fields);
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
                username: $result['username'],
                password: $result['password'],
                email: $result['email'],
                imgFileName: $result['imgFileName'],
                description: $result['description']
            );
        }
    }

    public function checkContact(&$response, $validator)
    {
        if ($validator->validate($response['page'])) {
            $result = $validator->getFieldInputs();
            ModelSelector::getWebsiteInfoModel()->saveContact(
                name: $result['name'],
                email: $result['email'],
                message: $result['message']
            );
        } else {
            $response['error'] = $validator->getErrors();
        }
    }
}
