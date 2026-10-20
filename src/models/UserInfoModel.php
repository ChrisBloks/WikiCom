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
        return $result;
    }

    /**
     * Fetch only user id from the database based on given email.
     * @param string $email
     * @return array|false
     */
    public function fetchUserIDByEmail(string $email): array|false
    {
        $sql = "SELECT id FROM user 
                        WHERE email=:email";
        $params = ["email" => $email];
        $result = $this->crud->selectOne($sql, $params);
        return $result;
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

        $sql = "INSERT INTO user (name,password,email) 
                        VALUES (:username,:password,:email)";
        $params = [
            "username" => $username,
            "password" => $password,
            "email" => $email
        ];
        $result = $this->crud->doInsert($sql, $params);
        if ($result == false) {
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
    public function saveUserAboutInfo(string $imgFileName, string $description, string $author_id): string|false
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
        $result = $this->crud->doUpdate($sql, $params);
        if (empty($result)) {
            $this->logError("About info is not saved");
            $result = false;
        }
        return $result;
    }

    /**
     * Fetches a user's info from the database based on user_id
     * @param int $user_id
     * @return array|false
     */
    public function fetchUserPublicInfoById(int $user_id): array|false
    {
        $sql = "SELECT user.name,
                       user.email,
                       user.imgFileName,
                       user.description
                FROM user 
                WHERE id=:user_id";
        $params = ['user_id' => $user_id];
        $result = $this->crud->selectOne($sql, $params);

        if (empty($result)) {
            $this->logError("No user with this id");
            $result = false;
        }
        return $result;
    }

    /**
     * Fetches a user's info from the database based on user_id
     * @param int $user_id
     * @return array|false
     */
    public function fetchUserPrivateInfoById(int $user_id): array|false
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

    public function updateUserInfo(array $fields, int $user_id): array|bool
    {
        if (empty($fields)) {
            return false;
        }

        $setParts = [];
        $params = [];
        foreach ($fields as $column => $value) {
            $setParts[] = "`$column` = :$column";
            $params[$column] = $value;
        }
        $params['user_id'] = $user_id;

        $sql = "UPDATE user SET " . implode(', ', $setParts) . " WHERE id = :user_id";

        $result = $this->crud->doUpdate($sql, $params);

        if ($result === false) {
            $this->logError("Failed to update user info");
            return false;
        }

        // $result is an int here (0 or more) — query executed successfully either way
        return true;
    }


    public function fetchAllUsers(): array|false
    {

        $sql = "SELECT `name` FROM user";
        $params = [];
        $result = $this->crud->selectMany($sql, $params);

        if ($result == false) {
            $this->logError("Failed to fetch users");
            $result = false;
        }
        return $result;
    }

    public function saveAPIKey(string $user_id, string $api_key): int|false
    {

        $sql = "INSERT INTO api_keys (user_id,api_key,creation_date) 
                        VALUES (:user_id,:api_key,:creation_date)";
        $params = [
            "user_id" => $user_id,
            "api_key" => $api_key,
            "creation_date" => date(format: 'Y-m-d')
        ];
        $result = $this->crud->doInsert($sql, $params);
        if ($result == false) {
            $this->logError("Api save failed");
            $result = false;
        }
        return $result;
    }

    public function checkAPIKey(string $api_key)
    {
        $sql = "SELECT * FROM api_keys 
                        WHERE api_key=:api_key";
        $params = ['api_key' => $api_key];
        $result = $this->crud->selectOne($sql, $params);

        if (empty($result)) {
            $this->logError("No api key");
            $result = false;
        }
        return $result;
    }

    public function addCountAPIKey(string $api_key)
    {
        $sql = "UPDATE api_keys
                SET last_used_date = :last_used_date,
                    count = count + 1
                WHERE api_key = :api_key";
        ;
        $params = [
            "last_used_date" => date(format: 'Y-m-d'),
            "api_key" => $api_key
        ];
        $result = $this->crud->doUpdate($sql, $params);
        if (empty($result)) {
            $this->logError("Request is not counted");
            $result = false;
        }
        return $result;
    }

    public function fetchAPIKeyByUser(string $user_id)
    {
        $sql = "SELECT api_key FROM api_keys 
                        WHERE api_keys.user_id=:user_id";
        $params = ["user_id" => $user_id];
        $result = $this->crud->selectOne($sql, $params);
        return $result;
    }
}
