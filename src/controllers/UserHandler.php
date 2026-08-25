<?php

class UserHandler
{
    use tSingleton;
    public function checkLogin(&$response, $validator)
    {
        if ($validator->validate($response['page'])) {
            $result = $validator->getFieldInputs();
            $userinfo = ModelSelector::getUserInfoModel()->fetchUserInfoByEmail($result['email']);
            if (password_verify($result['password'],$userinfo['password'])) {
                return $userinfo;              
            }
            else{
                $response['error'] = "Login email or password is wrong!";
            }
        } else {
            $response['error'] = $validator->getErrors();
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
