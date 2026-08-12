<?php
$user = $_ENV["USERDOMAIN"];
switch ($user) {
        case "DANNY":
                include_once "../config/danny.php";
                break;
        case "":
                break;
        case "":
                break;
}
require_once "Crud.php";
require_once "BaseModel.php";

class WebsiteInfoModel extends BaseModel
{

    public function getBodyText($page_value, $user_id = '')
    {
        if ($page_value == 'home') {
            $sql = "SELECT bodytext FROM website_info 
                    WHERE name=:page";
            $params = ["page" => $page_value];
            return $this->crudTemp->selectOne($sql, $params);
        } elseif ($page_value == "about") {
            $sql = "SELECT name,description,imgFileName FROM user 
                    WHERE id=:userid";
            $params = ["userid" => $user_id];
            return $this->crudTemp->selectOne($sql, $params);
        } else {
            return false;
        }

    }

    public function saveContact(string $name, string $email, string $message)
    {
        $sql = "INSERT INTO contact_messages (name,message,email,date) 
                VALUES (:name,:message,:email,:date)";
        $params = [
            "name" => $name,
            "message" => $message,
            "email" => $email,
            "date" => date('Y-m-d'),
        ];
        return $this->crudTemp->insert($sql, $params);
    }
}
