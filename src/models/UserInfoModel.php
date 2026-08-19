<?php
/* UserModel
*  Danny
*  08/2026
*  UserModel class gives al the methods needed to pull or insert User information from database
*/
require_once "Crud.php";
require_once "BaseModel.php";
class UserInfoModel extends BaseModel
{
        /*
        * method that checks login of user if it's correct a username and id will be given otherwise false
        *
        * @params username, password
        */ 
        public function loginUser(string $username, string $password)
        {

                $sql = "SELECT id,name,password FROM user 
                        WHERE name=:username";
                $params = ["username" => $username];
                $result = $this->crudTemp->selectOne($sql, $params);

                if ($result and $result["password"] == $password) {
                        return ["id" => $result["id"], "name" => $result["name"]];
                }
                return false;
        }

        /*
        * method that checks if email already exists if true then it doens't exist otherwise false
        *
        * @params email
        */ 
        public function checkEmail (string $email)
        {
                $sql = "SELECT email FROM user 
                        WHERE email=:email";
                $params = ["email"=> $email];
                $result = $this->crudTemp->selectOne($sql, $params);
                return empty($result);
        }

        /*
        * method that registers users name email filename and description to the database
        *
        * @params username,password,email,imfilename,description
        */ 
        public function registerUser(string $username, string $password, string $email, string $imgFileName,string $description)
        {
                $sql = "INSERT INTO user (name,password,email,imgFileName,description) 
                        VALUES (:username,:password,:email,:imgFileName,:description)";
                $params = [
                            "username" => $username,
                            "password" => $password,
                            "email" => $email,
                            "imgFileName" => $imgFileName,
                            "description" => $description
                ];
                $result = $this->crudTemp->insert($sql, $params);
                if (empty($result)) {
                $this->logError("registration failed");
                $result = false;
                }
                return $result;
        }

        /*
        * method that fetches all the users data based on user_id
        *
        * @params user_id
        */ 
        public function fetchUserInfoById (int $user_id)
        {
                $sql = "SELECT * FROM user 
                        WHERE id=:user_id";
                $params = ['user_id' => $user_id];
                $result = $this->crudTemp->selectOne($sql, $params);
                if (empty($result)) {
                $this->logError("No user with this id");
                $result = false;
                }
                return $result;
        }

}