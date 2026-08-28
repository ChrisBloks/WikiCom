<?php
/* UserModel
 *  Danny
 *  08/2026
 *  UserModel class gives al the methods needed to pull or insert User information from database
 */

namespace Wiki\models;

use Wiki\tools\utils\HtmlUtils;

class UserInfoModel extends BaseModel
{

    /**
     * Fetch user info from the database based on given email.
     * @param string $email
     * @return array|false
     */
    public function fetchUserInfoByEmail(string $email): array|false
    {
        $sql = "SELECT id,name,password FROM user 
                        WHERE email=:email";
        $params = ["email" => $email];
        $result = $this->crud->selectOne($sql, $params);

        if (empty($result)) {
            $this->logError("no account with this email");
            $result = false;
        }
        return $result;
    }

    /**
     * Check if an email is already present in the database.
     * True if an email was matched, false otherwise.
     * @param string $email
     * @return bool
     */
    public function checkEmailExists(string $email): bool
    {
        $sql = "SELECT email FROM user 
                        WHERE email=:email";
        $params = ["email" => $email];
        $result = $this->crud->selectOne($sql, $params);
        return !empty($result);
    }


    /**
     * Save a new user to the database.
     * @param string $username
     * @param string $password
     * @param string $email
     * @return int|false
     */
    public function saveUser(string $username, string $password, string $email): int|false
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (name,password,email) 
                        VALUES (:username,:password,:email)";
        $params = [
            "username" => $username,
            "password" => $hashed_password,
            "email" => $email
        ];
        $result = $this->crud->doInsert($sql, $params);
        if ($result==false) {
            $this->logError("registration failed");
            $result = false;
        }
        return $result;
    }


    /**
     * Update user about description and image
     * @param string $imgFileName
     * @param string $description
     * @param string $author_id
     * @return string|false
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
        $result = $this->crud->doInsert($sql, $params);
        if (empty($result)) {
            $this->logError("registration failed");
            $result = false;
        }
        return $result;
    }

    /**
     * Fetches a user's info from the database based on user_id
     * @param int $user_id
     * @return array|false
     */
    public function fetchUserInfoById(int $user_id): array|false
    {
        $sql = "SELECT * FROM user 
                        WHERE id=:user_id";
        $params = ['user_id' => $user_id];
        $result = $this->crud->selectOne($sql, $params);
        if (empty($result)) {
            $this->logError("No user with this id");
            $result = false;
        }
        return $result;
    }
}
