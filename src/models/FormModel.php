<?php
require_once "Crud.php";
require_once "BaseModel.php";
class FormModel extends BaseModel
{
    public function getFieldInfo($page_value)
    {
        $sql = "SELECT  fi.type, 
                        fi.name, 
                        fi.class, 
                        fi.label, 
                        li.*
                FROM field_info fi
                JOIN form_info fo ON fi.form_info_id = fo.id
                JOIN website_info wi ON wi.id = fo.website_info_id
                LEFT JOIN lookup_info li on li.id = fi.lookup_info_id
                WHERE wi.name = :page
                ORDER BY fi.display_order;";
        $params = ["page" => $page_value];
        $result = $this->crudTemp->selectMany($sql, $params);

        if (empty($result)) {
            $this->logError("Page has no Form");
            return false;
        }
        foreach ($result as $key => $value) {
            if (isset($value["id"])) {
                $lookup = $this ->getLookupInfo($value["table_name"],$value["column_names"],$value["order_by"]);
                $result[$key]["options"] = $lookup;
        
        }
        }
        return $result;
    }

        public function getFormInfo($page_value)
    {
        $sql = "SELECT DISTINCT fo.action, 
                                fo.method, 
                                fo.submit_caption
                FROM form_info fo
                JOIN website_info wi ON fo.website_info_id = wi.id
                WHERE wi.name = :page";
        $params = ["page" => $page_value];
        $result = $this->crudTemp->selectMany($sql, $params);

        return $result[0];
    }

    public function getLookupInfo($table_name,$column_names,$order_by)
    {
        $sql = "SELECT $column_names FROM $table_name ORDER BY $order_by";
        $result = $this->crudTemp->selectMany($sql,NULL,PDO::FETCH_KEY_PAIR);
        if (empty($result)) {
            $this->logError("No tags");
            $result = false;
        }
        return $result;
    }

}
