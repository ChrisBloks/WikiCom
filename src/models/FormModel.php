<?php
require_once "Crud.php";
require_once "BaseModel.php";
class FormModel extends BaseModel
{
    public function getFieldInfo($page_value, $id = '')
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
        foreach ($result as &$value) {
            if (isset($value["id"])) {
                $this->getLookupInfo($value, $id);
            }
        }
        unset($value);
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

    public function getLookupInfo(&$value, $id)
    {
        if (!empty($value["table_name"])) {
            $sql = "SELECT {$value["display_names"]} FROM {$value['table_name']}";

            if (!empty($value["value"])) {

                if (!empty($value["bridgejoin"])) {
                    [$main, $bridge] = explode(",", $value["bridgevalues"]);
                    $sql .= " JOIN {$value["bridgejoin"]} ON {$main} = {$bridge}";
                }
                $sql .= " WHERE {$value['value']} = {$id}";

                $sql .= " ORDER BY {$value['order_by']}";
            }

            $result = $this->crudTemp->selectMany($sql, NULL, PDO::FETCH_KEY_PAIR);
            if (empty($result)) {
                $result = array();
            }

            $value["options"] = $result;
        } else {
            $value["options"] = explode(",", $value["display_names"]);
        }


    }

}
