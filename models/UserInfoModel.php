<?php
require_once "Crud.php";
require_once "BaseModel.php";
class UserInfoModel extends BaseModel
{
        public function loginUser(string $username, string $password)
        {
                $sql = "SELECT id,name,password FROM user 
                        WHERE name=:username";
                $params = ["username" => $username];
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result and $result["password"] == $password) {
                        return ["id" => $result["id"], "name" => $result["name"]];
                }
                return false;
        }
        public function checkEmail (string $email)
        {
                $sql = "SELECT email FROM user 
                        WHERE email=:email";
                $params = ["email"=> $email];
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return empty($result);
        }

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
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);
                return $this->crudTemp->db->lastInsertId();
        }

        public function fetchUserInfoById (int $user_id)
        {
                $sql = "SELECT * FROM user 
                        WHERE id=:user_id";
                $params = ['user_id' => $user_id];
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

}