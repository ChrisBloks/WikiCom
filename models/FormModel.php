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


class FormModel extends BaseModel
{
    public function getFieldInfo($page_value)
    {
        if (in_array($page_value, ['login', 'register', 'contact', 'search'])) {
            $sql = "SELECT fi.type, fi.name,fi.class,fi.label,fi.options
                    FROM field_info fi
                    JOIN fields_per_page fpp ON fpp.field_info_id = fi.id
                    JOIN website_info wi ON wi.id = fpp.website_info_id
                    WHERE wi.name = :page";
            $params = ["page" => $page_value];
            $stmt = $this->crudTemp->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            for ($i = 0; $i < count($result); $i++){
                if($result[$i]["options"]){
                    
                    $result[$i]["options"] = $this->getTag();
                }
                else{
                    unset($result[$i]["options"]);
                }
            }
            return $result;
        }
    }

    public function getTag()
        {
                $sql = "SELECT * FROM tag";
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
                return $result;
        }
}

$test = new FormModel();
print_r($test->getFieldInfo("login")[2]);
