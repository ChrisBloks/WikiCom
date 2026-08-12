<?php
require_once "Crud.php";
require_once "BaseModel.php";

class FormModel extends BaseModel
{
    public function getFieldInfo($page_value)
    {
    if (in_array($page_value, ['login','register','contact','search'])) {
        $sql = "SELECT fi.name, fi.type
        FROM field_info fi
        JOIN fields_per_page fpp ON fpp.field_info_id = fi.id
        JOIN website_info wi ON wi.id = fpp.website_info_id
        WHERE wi.name = :page";
        $params = ["page" => $page_value];
        $stmt = $this->crudTemp-> db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $result;
    }
    }
    
}
