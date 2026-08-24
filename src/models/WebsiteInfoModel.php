<?php
/* WebsiteModel
*  Danny
*  08/2026
*  WebsiteModel class gives al the methods needed to pull Website information from database
*/
require_once "Crud.php";
require_once "BaseModel.php";

class WebsiteInfoModel extends BaseModel
{
    /*
    * method gets body text based on the page name
    *
    * @params page name
    */ 
    public function fetchBodyText($page_name)
    {
        $sql = "SELECT bodytext
                FROM website_info 
                WHERE name=:page";
        $params = ["page" => $page_name];
        $result = $this->crudTemp->selectOne($sql, $params);
        if (empty($result)){
            $this -> logError("Page has no Body text");
            return false;
        }

        $result = array_merge($result,$this->fetchClasses($page_name));
        
        return $result;
        
    }

    /*
    * method gets user info based on user id
    *
    * @params user id
    */ 
    public function fetchAuthorAboutInfo($user_id, $page_name ="about")
    {
        $sql = "SELECT name,description,imgFileName FROM user 
                WHERE id=:userid";
        $params = ["userid" => $user_id];
        $result = $this->crudTemp->selectOne($sql, $params);
        if (empty($result)){
            $this -> logError("User has no info");
            return false;
        }

        $result = array_merge($result,$this->fetchClasses($page_name));

        return $result;

    }
    /*
    * method that saves contact name,email and message
    *
    * @params name, email , message
    */ 
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
        return $this->crudTemp->doInsert($sql, $params);
    }
    /*
    * method that grabs the menu items from the database based on if the user is logged in
    *
    * @params isloggedIn bool that indicates if person is logged in
    */ 
    public function fetchMenuItems($isLoggedIn)
{
    $excluded = $isLoggedIn ? ['Register', 'Login'] : ['Dashboard','Logout'];
    $placeholders = implode(',', array_fill(0, count($excluded), '?'));


    $sql = "SELECT mi.label, mi.href
            FROM menu_items mi
            WHERE mi.label NOT IN ($placeholders)
            ORDER BY mi.display_order";
    $result = $this->crudTemp->selectMany($sql, $excluded);

    $authorlist = [];
    foreach ($this->fetchAuthor() as $id => $name) {
        $authorlist[] = ["label" => $name, "href" => "about&author=".$id.""];
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
    /*
    * method that gets all authors
    *
    */ 
    public function fetchAuthor()
    {
        $sql = "SELECT id,name FROM user ORDER BY user.name";
        return $this->crudTemp->selectMany($sql,NULL,PDO::FETCH_KEY_PAIR);
    }
    /*
    * method that gets all table column info
    *
    */ 
    public function fetchTableColumns($columns)
    {
        $placeholders = implode(',', array_fill(0, count($columns), '?'));
        $sql = "SELECT `column_name`,
                       `column_title`, 
                       `display_type`,
                       `class_types`,
                       `column_headers`
                       FROM table_columns
                       WHERE column_name in ($placeholders)";
        $result = $this->crudTemp->selectMany($sql, $columns, PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC);

        return $result;

    }

    public function fetchClasses($page_name)
    {
        $sql = "SELECT class_name, class 
                FROM display_classes as dc
                JOIN  website_info as wi on wi.id = dc.website_info_id
                WHERE wi.name = :page";
        $params = ["page" => $page_name];
        $classrows = $this->crudTemp->selectMany($sql, $params);

        $classes = [];
        foreach ($classrows as $row) {
            $classes[$row['class_name']] = $row['class'];
        }


        return $classes;
    

    }
}
