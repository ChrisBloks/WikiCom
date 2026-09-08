<?php
/* WebsiteModel
*  Danny
*  08/2026
*  WebsiteModel class gives al the methods needed to pull Website information from database
*/

namespace Wiki\models;

use Wiki\tools\utils\HtmlUtils;

class WebsiteInfoModel extends BaseModel
{


    /*
    * method gets body text based on the page name
    *
    * @params page name
    */
    public function fetchBodyText(string $page_name): array|false
    {
        $sql = "SELECT bodytext
                FROM website_info 
                WHERE name=:page";
        $params = ["page" => $page_name];
        $result = $this->crud->selectOne($sql, $params);
        if (empty($result)) {
            $this->logError("Page has no Body text");
            return false;
        }


        return $result;
    }

    /*
    * method gets user info based on user id
    *
    * @params user id
    */
    public function fetchAuthorAboutInfo(string $user_id, string $page_name = "about"): array|false
    {
        $sql = "SELECT name,description,imgFileName FROM user 
                WHERE id=:userid";
        $params = ["userid" => $user_id];
        $result = $this->crud->selectOne($sql, $params);
        if (empty($result)) {
            $this->logError("User has no info");
            return false;
        }

        return $result;
    }
    /*
    * method that saves contact name,email and message
    *
    * @params name, email , message
    */
    public function saveContact(string $name, string $email, string $message): string|false
    {
        $sql = "INSERT INTO contact_messages (name,message,email,date) 
                VALUES (:name,:message,:email,:date)";
        $params = [
            "name" => $name,
            "message" => $message,
            "email" => $email,
            "date" => date('Y-m-d'),
        ];
        return $this->crud->doInsert($sql, $params);
    }
    /*
    * method that grabs the menu items from the database based on if the user is logged in
    *
    * @params isloggedIn bool that indicates if person is logged in
    */
    public function fetchMenuItems(bool $isLoggedIn): array
    {
        $excluded = $isLoggedIn ? ['Register', 'Login'] : ['Dashboard', 'Logout'];
        $placeholders = implode(',', array_fill(0, count($excluded), '?'));


        $sql = "SELECT mi.label, mi.href
            FROM menu_items mi
            WHERE mi.label NOT IN ($placeholders)
            ORDER BY mi.display_order";
        $result = $this->crud->selectMany($sql, $excluded);

        $authorlist = [];
        foreach ($this->fetchAuthor() as $id => $name) {
            $authorlist[] = ["label" => $name, "href" => "about&author=" . $id . ""];
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
        return $this->crud->selectMany($sql, NULL, \PDO::FETCH_KEY_PAIR);
    }
    /*
    * method that gets all table column info
    *
    */
    public function fetchTableColumns(array $columns): array
    {
        $placeholders = implode(',', array_fill(0, count($columns), '?'));
        $sql = "SELECT `column_name`,
                       `column_title`, 
                       `display_type`,
                       `class_types`,
                       `column_headers`,
                       `href`
                       FROM table_columns
                       WHERE column_name in ($placeholders)
                       ORDER BY display_order";
        $result = $this->crud->selectMany($sql, $columns, \PDO::FETCH_UNIQUE | \PDO::FETCH_ASSOC);

        return $result;
    }

    public function fetchElementStylingByPage(string $page_name): array
    {
        $sql = "SELECT class_name, class 
                FROM styling_elements as se
                JOIN  website_info as wi on wi.id = se.website_info_id
                WHERE wi.name = :page";
        $params = ["page" => $page_name];
        $classrows = $this->crud->selectMany($sql, $params);

        $classes = [];
        foreach ($classrows as $row) {
            $classes[$row['class_name']] = $row['class'];
        }


        return $classes;
    }

    public function fetchContainerStylingByPage(string $page_name):array
    {
        $sql = "SELECT styling.name, styling.styling
                FROM styling_containers as styling
                JOIN website_info_to_styling_containers as wits on wits.styling_id = styling.id
                JOIN website_info as wi on wi.id = wits.website_info_id
                WHERE wi.name = :page";
        $params = ["page" => $page_name];
        $stylingrows = $this->crud->selectMany($sql,$params);

        $styling = [];
        foreach ($stylingrows as $row){
            $styling[$row['name']] = $row['styling'];
        }

        return $styling;
    }

    public function fetchSystemStyling():array
    {
        $sql = "SELECT styling_system.name, styling_system.styling
                FROM styling_system";
        $stylingrows = $this->crud->selectMany($sql,NULL);
        $styling = [];
        foreach ($stylingrows as $row){
            $styling[$row['name']] = $row['styling'];
        }

        return $styling;
    }

    public function fetchElementInfoByPage(string $page_name): array {

    }
}
