<?php
require_once "Crud.php";
require_once "BaseModel.php";

class WebsiteInfoModel extends BaseModel
{

    public function getBodyText($page_value, $user_id = '')
    {
        if ($page_value == 'about') {
            $sql = "SELECT name,description,imgFileName FROM user 
                    WHERE id=:userid";
            $params = ["userid" => $user_id];
            $result = $this->crudTemp->selectOne($sql, $params);
        } 
        else  {
            $sql = "SELECT bodytext FROM website_info 
                    WHERE name=:page";
            $params = ["page" => $page_value];
            $result = $this->crudTemp->selectOne($sql, $params);
        }
        if (empty($result)){
            $this -> logError("Page has no Body text");
            return false;
        }
        return $result;
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
    $excluded = $isLoggedIn ? ['Register', 'Login'] : ['Dashboard'];
    $placeholders = implode(',', array_fill(0, count($excluded), '?'));

    $sql = "SELECT mi.label, mi.href
            FROM menu_items mi
            WHERE mi.label NOT IN ($placeholders)
            ORDER BY mi.display_order";
    $result = $this->crudTemp->selectMany($sql, $excluded);

    $authorlist = [];
    foreach ($this->getAuthor() as $id => $name) {
        $authorlist[] = ["label" => $name, "href" => "?page=about&author=".$id.""];
    }

    foreach ($result as &$item) {
        if ($item['label'] === 'About') {
            $item['submenu'] = $authorlist;
            break;
        }
    }
    unset($item);

    return $result;
}

    public function getAuthor()
    {
        $sql = "SELECT id,name FROM user ORDER BY user.name";
        return $this->crudTemp->selectMany($sql,NULL,PDO::FETCH_KEY_PAIR);
    }

    public function getTableColumns()
    {
        $sql = "SELECT column_key, label, type FROM table_columns ORDER BY display_order";
        return $this->crudTemp->selectMany($sql, NULL, PDO::FETCH_ASSOC);
    }
}
