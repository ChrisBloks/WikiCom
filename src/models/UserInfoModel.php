<?php
/* UserModel
 *  Danny
 *  08/2026
 *  UserModel class gives al the methods needed to pull or insert User information from database
 */

namespace Wiki\models;

class UserInfoModel extends BaseModel
{
    /*
    * method that gives user info based on email
    *
    * @params email
    */
    public function fetchUserInfoByEmail(string $email): array|false
    {

        $sql = "SELECT id,name,password FROM user 
                        WHERE email=:email";
        $params = ["email" => $email];
        $result = $this->crudTemp->selectOne($sql, $params);

        if (empty($result)) {
            $this->logError("no account with this email");
            $result = false;
        }
        return $result;
    }

    /*
    * method that checks if email already exists if true then it doens't exist otherwise false
    *
    * @params email
    */
    public function checkEmailExists(string $email): bool
    {
        $sql = "SELECT email FROM user 
                        WHERE email=:email";
        $params = ["email" => $email];
        $result = $this->crudTemp->selectOne($sql, $params);
        return $result;
    }

    /*
    * method that registers users name email filename and description to the database
    *
    * @params username,password,email,imfilename,description
    */
    public function saveUser(string $username, string $password, string $email): array|false
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (name,password,email) 
                        VALUES (:username,:password,:email)";
        $params = [
            "username" => $username,
            "password" => $hashed_password,
            "email" => $email
        ];
        $result = $this->crudTemp->doInsert($sql, $params);
        if (empty($result)) {
            $this->logError("registration failed");
            $result = false;
        }
        return $result;
    }

    /*
    * method that changes and saves new user about description and image
    *
    * @params imfilename,description
    */
    public function saveUserAboutInfo(string $imgFileName, string $description,string $author_id): string|false
    {

        $sql = "UPDATE user 
                SET imgFileName = :imgFileName,
                    description = :description
                WHERE id = :author_id";
                
        $params = [
            "author_id" => $author_id,
            "imgFileName" => $imgFileName,
            "description" => $description
        ];
        $result = $this->crudTemp->doInsert($sql, $params);
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
    public function fetchUserInfoById(int $user_id): array|false
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
