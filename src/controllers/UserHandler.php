<?php

class UserHandler
{
    use tSingleton;
    public function checkLogin(&$response, $validator)
    {
        $form_fields = ModelSelector::getFormModel()->fetchFormFields($response['page']);
        $validator = new UserValidator($response, $form_fields);
        $result = $validator->validateLogin($response, $form_fields);
        if ($result['ok']) {
            $response['page'] = 'home';
            $_SESSION['username'] = $result['name'];
            $_SESSION['userID'] = $result['id'];
        }
    }

    public function checkRegistration(&$response, $validator)
    {
        if ($validator->validate($response['page'])) {
            $result = $validator->getFieldInputs();
            if (ModelSelector::getUserInfoModel()->checkEmail($result['email'])) {
                ModelSelector::getUserInfoModel()->registerUser(
                    username: $result['name'],
                    password: $result['password'],
                    email: $result['email'],
                    imgFileName: '',
                    description: ''
                );
            }
            else{
                $response['error'] = "Email already exists!";
            }
        } else {
            $response['error'] = $validator->getErrors();
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
