<?php
$user = $_ENV["USERDOMAIN"];
switch ($user) {
    case "DANNY":
        include_once "./config/danny.php";
        break;
    case "":
        break;
    case "":
        break;
}

require_once "Crud.php";
require_once "BaseModel.php";


class FormModel extends BaseModel
{
    public function getFieldInfo($page_value)
    {
        if (in_array($page_value, ['login', 'register', 'contact', 'search'])) {
            $sql = "SELECT fi.type, fi.name,fi.class,fi.label,fi.options
                    FROM field_info fi
                    JOIN fields_per_page fpp ON fpp.field_info_id = fi.id
                    JOIN website_info wi ON wi.id = fpp.website_info_id
                    WHERE wi.name = :page
                    ORDER BY fi.display_order";
            $params = ["page" => $page_value];
            $result = $this->crudTemp->selectMany($sql, $params);

            for ($i = 0; $i < count($result); $i++) {
                if ($result[$i]["options"] == 1) {

                    $result[$i]["options"] = $this->getTag();
                }
                elseif ($result[$i]["options"] == 2) {

                    $result[$i]["options"] = $this->getAuthor();
                }
            }
            return $result;
        }
    }

    public function getTag()
    {
        $sql = "SELECT * FROM tag ORDER BY tag.name";
        return $this->crudTemp->selectMany($sql,NULL,PDO::FETCH_KEY_PAIR);
    }

        public function getAuthor()
    {
        $sql = "SELECT id,name FROM user ORDER BY user.name";
        return $this->crudTemp->selectMany($sql,NULL,PDO::FETCH_KEY_PAIR);
    }
}
