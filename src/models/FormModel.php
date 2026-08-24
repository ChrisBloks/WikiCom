<?php
/* FormModel
*  Danny
*  08/2026
*  FormModel class gives al the methods needed to pull information from database about forms
*/
require_once "Crud.php";
require_once "BaseModel.php";
class FormModel extends BaseModel
{
    /*
    * Gets the necessary field information for a certain page. Some fields need an extray sub array as information
    * so when lookup_id exists a second query will be run based on the info gotten from the first query.
    *
    * @params page_name = the page name and id is used for further query building using the lookupinfo
    */ 
    public function fetchFieldInfo($page_name, $id = '')
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
        $params = ["page" => $page_name];
        $result = $this->crudTemp->selectMany($sql, $params);

        if (empty($result)) {
            $this->logError("Page has no Form");
            return false;
        }
        foreach ($result as &$value) {
            if (isset($value["id"])) {
                $this->fetchLookupInfo($value, $id);
            }
        }
        unset($value);
        return $result;
    }

    /*
    * Gets the necessary form information for a certain page. 
    *
    * @params page_name = the page name
    */ 
    public function fetchFormInfo($page_name)
    {
        $sql = "SELECT DISTINCT fo.action, 
                                fo.method, 
                                fo.submit_caption,
                                fo.display_class
                FROM form_info fo
                JOIN website_info wi ON fo.website_info_id = wi.id
                WHERE wi.name = :page";
        $params = ["page" => $page_name];
        $result = $this->crudTemp->selectMany($sql, $params);

        return $result[0];
    }

    /*
    * Method that builds and executes a query that relies on information gotten from the previously ran query
    *
    * @params $value = the information from the previous query given to this method to build the query
    *         $id = id given for certain queries that need specific id example: finding the tag for a certain article needs article_id
    */ 
    public function fetchLookupInfo(&$value, $id)
    {
        if (!empty($value["table_name"])) {
            $sql = "SELECT {$value["display_names"]} FROM {$value['table_name']}";

            if (!empty($value["value"])) {

                if (!empty($value["bridge_table"])) {
                    [$main, $bridge] = explode(",", $value["bridgevalues"]);
                    $sql .= " JOIN {$value["bridge_table"]} ON {$main} = {$bridge}";
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
