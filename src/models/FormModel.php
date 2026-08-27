<?php
/* FormModel
 *  Danny
 *  08/2026
 *  FormModel class gives al the methods needed to pull information from database about forms
 */

namespace Wiki\models;

class FormModel extends BaseModel
{
    /*
     * Gets the necessary field information for a certain page. Some fields need an extray sub array as information
     * so when lookup_id exists a second query will be run based on the info gotten from the first query.
     *
     * @params page_name = the page name and id is used for further query building using the lookupinfo
     */
    public function fetchFieldInfo(string $page_name, string $id = ''): array|false
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
                WHERE wi.name = :_page
                ORDER BY fi.display_order;";
        $params = ["_page" => $page_name];
        $result = $this->crudTemp->selectMany($sql, $params);

        if (empty($result)) {
            $this->logError("Page has no Form");
            return false;
        }
        foreach ($result as &$field_info) {
            if (isset($field_info["id"])) {
                $this->fetchLookupInfo($field_info, $id);
            }
        }
        unset($field_info);
        return $result;
    }

    /*
     * Gets the necessary form information for a certain page. 
     *
     * @params page_name = the page name
     */
    public function fetchFormInfo(string $page_name): array
    {
        $sql = "SELECT DISTINCT fo.action, 
                                fo.method, 
                                fo.submit_caption,
                                fo.enctype,
                                fo.display_class
                FROM form_info fo
                JOIN website_info wi ON fo.website_info_id = wi.id
                WHERE wi.name = :_page";
        $params = ["_page" => $page_name];
        $result = $this->crudTemp->selectMany($sql, $params);

        return $result[0];
    }

    /*
     * Method that builds and executes a query that relies on information gotten from the previously ran query
     *
     * @params $value = the information from the previous query given to this method to build the query
     *         $id = id given for certain queries that need specific id example: finding the tag for a certain article needs article_id
     */

    // TODO refactor
    public function fetchLookupInfo(array &$field_info, string $id): void
    {
        if (!empty($field_info["table_name"])) {
            $sql = "SELECT {$field_info["display_names"]} FROM {$field_info['table_name']}";

            if (!empty($field_info["bridge_table"])) {
                [$main, $bridge] = explode(",", $field_info["bridgevalues"]);
                if (!empty($field_info["left_on"])) {
                    $sql .= "LEFT JOIN {$field_info["bridge_table"]} ON {$main} = {$bridge} AND {$field_info["left_on"]} ={$id} ";
                } else {
                    $sql .= "JOIN {$field_info["bridge_table"]} ON {$main} = {$bridge}";
                }
            }
            if (!empty($field_info["value"])) {
                $sql .= " WHERE {$field_info['value']} = {$id}";
            }

            $sql .= " ORDER BY {$field_info['order_by']}";


            $result = $this->crudTemp->selectMany($sql, NULL, \PDO::FETCH_ASSOC);

            if ($result === false) {
                $result = array();
            }

            $options = [];
            $marked = [];
            foreach ($result as $row) {
                if (isset($row['marked'])) {
                    $marked[$row['id']] = $row['marked'];
                }
                $options[$row['id']] = $row['name'];
            }

            $field_info["options"] = $options;
            $field_info["value"] = $marked;
        } else {
            $field_info["options"] = explode(",", $field_info["display_names"]);
        }
    }

    public function fetchFieldNames(string $page_name): array|false
    {
        $sql = "SELECT  fi.name 
                FROM field_info fi
                JOIN form_info fo ON fi.form_info_id = fo.id
                JOIN website_info wi ON wi.id = fo.website_info_id
                WHERE wi.name = :_page
                ORDER BY fi.display_order;";
        $params = ["_page" => $page_name];
        $result = $this->crudTemp->selectMany($sql, $params, \PDO::FETCH_COLUMN);

        if (empty($result)) {
            $this->logError("Page has no Form");
            return false;
        }
        unset($value);
        return $result;
    }
}
