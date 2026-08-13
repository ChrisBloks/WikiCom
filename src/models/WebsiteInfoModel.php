<?php
$user = $_ENV["USERDOMAIN"];
switch ($user) {
        case "DANNY":
                include_once "./config/danny.php";
                break;
        case "MARUISPC":
            include_once "./config/marius.php";
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

    public function getMenuItems($isLoggedIn)
    {
        if ($isLoggedIn==FALSE) {
            $sql = "SELECT mi.label, mi.href
                    FROM menu_items mi
                    Where mi.label != 'Dashboard'
                    ORDER BY mi.display_order";
            $result = $this->crudTemp->selectMany($sql,NULL);
            $author = $this->getAuthor();
            $authorlist =[];
            foreach ($author as $id => $name) 
            {
                $authorlist[]=["label"=> $name,"href"=> 'index.php?page=about&author='.$id.''];
            }
            $result[1] = array_merge($result[1],["submenu" => $authorlist]);
    
            return $result;     
        }
        else {
            $sql = "SELECT mi.label, mi.href
                    FROM menu_items mi
                    Where mi.label != 'Register'
                    AND mi.label != 'Login'
                    ORDER BY mi.display_order";
            $result = $this->crudTemp->selectMany($sql,NULL);
            $author = $this->getAuthor();
            $authorlist =[];
            foreach ($author as $id => $name) 
            {
                $authorlist[]=["label"=> $name,"href"=> 'index.php?page=about&author='.$id.''];
            }
            $result[1] = array_merge($result[1],["submenu" => $authorlist]);
    
            return $result;   

        }
    }

    public function getAuthor()
    {
        $sql = "SELECT id,name FROM user ORDER BY user.name";
        return $this->crudTemp->selectMany($sql,NULL,PDO::FETCH_KEY_PAIR);
    }
}
