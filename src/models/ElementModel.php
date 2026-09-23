<?php
/* FormModel
 *  Danny
 *  08/2026
 *  FormModel class gives al the methods needed to pull information from database about forms
 */

namespace Wiki\models;

use InvalidArgumentException;
use Wiki\dataObjects\FormInfo,
Wiki\dataObjects\ElementInfo,
Wiki\dataObjects\Stack,
Wiki\dataObjects\FieldInfo;
use Wiki\tools\utils\HtmlUtils;

class ElementModel extends BaseModel
{
    public function fetchPageElements(string $page_name): array|false
    {
        $sql = "SELECT  p_e.order_by,
                        p_e.parent_order,
                        e_i.element_name,
                        e_i.html_tag,
                        e_i.html_class,
                        e_i.php_class,
                        e_i.js_class,
                        e_i.text,
                        e_i.id as element_id
                FROM page_elements as p_e
                JOIN page on p_e.page_id = page.id
                JOIN element_info as e_i on p_e.element_id = e_i.id
                WHERE page.name = :page
                ORDER BY p_e.order_by;";
        $params = ["page" => $page_name];

        
        $result = $this->crud->selectMany($sql, $params);
        

        if (empty($result)) {
            $this->logError("Page has no Elements");
            return false;
        }
        
        foreach ($result as $row => $element_info) {
            $element_info = $this->getLookupResult($element_info);
            $result[$row] = new ElementInfo($element_info);
        }
       

        return $result;
    }

    protected function getLookupResult(array $element_info){
        $element_id = $element_info['element_id'];
        $lookup_info_list = $this->fetchLookupInfoByElementId($element_id);
        
        foreach ($lookup_info_list as $lookup_info){
           
            switch($lookup_info['lookup_type']){
                case 'form':
                    $form_info = $this->fetchLookupInfoResult($lookup_info, mode: 'one');
                    $element_info['form_info'] = new FormInfo($form_info);
                    break;
                case 'field':
                    $field_info = $this->fetchLookupInfoResult($lookup_info, mode: 'one');
                    $element_info['field_info'] = new FieldInfo($field_info);
                    break;
                case 'element':
                    // get sub_element_id
                    $sub_element_info = $this->fetchLookupInfoResult($lookup_info, mode: 'one');
                    $element_info['sub_fields'][] = $this->getLookupResult($sub_element_info);
                    break;
                case 'options':
                    $options_info = $this->fetchLookupInfoResult($lookup_info, mode: 'many');
                    $element_info['options_info'][] = $options_info;
                default:
                    break;
            }
        }
        return $element_info;
    }

    /**
     * Fetches an article with the given user id
     * @param int $element_id
     * @return array|false a single article of form [id, title, lastEdit]
     */
    public function fetchLookupInfoByElementId(int $element_id): array|false
    {
        $sql = "SELECT  *
                    FROM element_lookup_info as e_l_i
                    WHERE element_id=:element_id";
        $params = ['element_id' => $element_id];
        $result = $this->crud->selectMany(sql: $sql, params: $params);
        return $result;
    }


    /**
     * Gets the necessary field information for a given page.
     * Some fields require an extra sub array as information.
     * Therefore, if 'lookup_id' exists within the result of the first query a second query will be run.
     * @param string $element_id
     * @return array|false
     */
    public function fetchFieldInfo(string $element_id): array|false
    {
        $result = [];
        $lookups_info = $this->fetchLookupInfoByElementId($element_id);
        foreach ($lookups_info as $lookup_info){
            if ($lookup_info['source_table'] !='form_info'){
            $lookup = $this->fetchLookupInfoResult($lookup_info, mode: 'one');
            $lookup_field = $this->getLookupResult($lookup);
            if ($lookup_field['field_info']['type']!='hidden'){
                $result[] = $lookup_field['field_info'];
            }
            }
        }

        return $result;
    }

    /**
     * Get the form information of a given page.
     * Returns a value for 'action', 'method', 'submit_caption', 'enctype', and 'display_class'
     * @param string $page_name
     * @return array ['action, 'method', 'submit_caption', 'encype', 'display_class']
     */
    public function fetchFormInfo(string $page_name): \arrayAccess|false
    {
        $sql = "SELECT DISTINCT fo.action, 
                                fo.method, 
                                fo.submit_caption,
                                fo.enctype,
                                fo.display_class,
                                fo.submit_class,
                                fo.id
                FROM form_info fo
                JOIN website_info wi ON fo.website_info_id = wi.id
                WHERE wi.name = :page";
        $params = ["page" => $page_name];
        $result = $this->crud->selectMany(sql: $sql, params: $params);

        // If the query was succesful, extract the first row
        if ($result !== false && count($result) == 1) {
            $result = $result[0];
        }

        $form_info = new FormInfo($result, true);

        return $form_info;
    }

    /**
     * @param array $lookup_info see INPUT
     * @return array see OUTPUT
     */
    public function fetchLookupInfoResult(array $lookup_info, string $mode = 'one'): array|false
    {
        // Basic SQL start
        $sql = "SELECT
                    {$lookup_info['column_names']}
                FROM
                    {$lookup_info['source_table']}
                ";

        // // If a bridge table is required
        // if (!empty($lookup_info["bridge_table"])) {
        //     [$bridge_table_column, $source_table_column] = explode(",", $lookup_info["bridge_values"]);

        //     $join_clause = "JOIN {$lookup_info["bridge_table"]} ON {$bridge_table_column} = {$source_table_column}";
        //     // If a LEFT JOIN is required
        //     if (!empty($lookup_info['left_join_on'])) {
        //         $join_clause = "LEFT " . $join_clause . " AND {$lookup_info["left_join_on"]} = {$parent_id}";
        //     }
        //     $sql .= $join_clause;
        // }

        // If a WHERE value is specified
        if (!empty($lookup_info["where_"])) {
            if (explode(',',$lookup_info['where_value']) > 1){
                $sql .= " WHERE {$lookup_info['where_']} IN ({$lookup_info['where_value']})";
            }
            else {
                $sql .= " WHERE {$lookup_info['where_']} = {$lookup_info['where_value']}";
            }
            
        }

        // // Always add an ORDER BY clause
        // $sql .= " ORDER BY {$lookup_info['order_by']}";
        // Execute the query
        // HtmlUtils::dump('sql', $sql);
        if ($mode === 'one'){
            $result = $this->crud->selectOne(sql: $sql, params: []);//, fetch_mode: \PDO::FETCH_ASSOC);
        } else if ($mode === 'many') {
            $result = $this->crud->selectMany(sql: $sql, params: []);
        } else {
            throw new InvalidArgumentException("mode = {$mode} is not a valid input parameter for fetchLookupInfoResult");
        }
        

                

        return $result;
    }



    /**
     * Get all fields belonging to a given wiki page
     * @param string $page_name
     * @return array|false array of page names (strings) if query succesful, false otherwise
     */
    public function fetchFieldNames(string $page_name): array|false
    {
        $sql = "SELECT  fi.name 
                FROM field_info fi
                JOIN form_info fo ON fi.form_info_id = fo.id
                JOIN website_info wi ON wi.id = fo.website_info_id
                WHERE wi.name = :page
                ORDER BY fi.display_order;";
        $params = ["page" => $page_name];
        $result = $this->crud->selectMany($sql, $params, \PDO::FETCH_COLUMN);

        if (empty($result)) {
            $this->logError("Page has no Form");
            return false;
        }
        unset($value);
        return $result;
    }
}


