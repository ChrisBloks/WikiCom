<?php
/* FormModel
 *  Danny
 *  08/2026
 *  FormModel class gives al the methods needed to pull information from database about forms
 */

namespace Wiki\models;

use InvalidArgumentException;
use Nette\Utils\Html;
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
                        e_i.html_id,
                        e_i.php_class,
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
            $result[$row] = new ElementInfo(
                $this->fetchLookupResult($element_info)
            );
        }


        return $result;
    }

    public function fetchElementVariables(int $element_id){
        // HtmlUtils::dump('element_info', $element_info);
        $sql = "SELECT 
                    `app_data`.`name`,
                    `app_data`.`element_info_key`
                FROM 
                    `element_to_application_data` AS `el_to_app_data`
                    JOIN `application_data` AS `app_data`
                        ON
                            `el_to_app_data`.`element_id`=:element_id AND
                            `el_to_app_data`.`application_data_id`=`app_data`.id;";
        $params = ['element_id' => $element_id];
        $result = $this->crud->selectMany(sql: $sql, params: $params); #, fetch_mode:\PDO::FETCH_COLUMN);
        
        $application_data = [];
        foreach($result as $row){ // array with keys 'name' and 'element_info_key'
            // HtmlUtils::dump('$result', $row);
            $application_data[$row['element_info_key']] = $row['name'];
        }

        return $application_data;
    }

    protected function fetchLookupResult(array $element_info)
    {
        $element_id = $element_info['element_id'];
        $lookup_info_list = $this->fetchLookupInfoByElementId($element_id);
        foreach ($lookup_info_list as $lookup_info) {

            switch ($lookup_info['lookup_type']) {
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
                    $element_info['sub_fields'][] = new ElementInfo($this->fetchLookupResult($sub_element_info));
                    break;
                case 'options':
                    $options_info = $this->fetchLookupInfoResult($lookup_info, mode: 'many');
                    $element_info['options_info'][] = $options_info;
                default:
                    break;
            }
        }
        if (isset($lookup_info['element_order'])){
            $element_info['element_order'] = $lookup_info['element_order'];
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
                    WHERE element_id=:element_id
                    ORDER BY element_order;";
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
        foreach ($lookups_info as $lookup_info) {
            if ($lookup_info['source_table'] != 'form_info') {
                $lookup = $this->fetchLookupInfoResult($lookup_info, mode: 'one');
                $lookup_field = $this->fetchLookupResult($lookup);
                if ($lookup_field['field_info']['type'] != 'hidden') {
                    $result[] = $lookup_field['field_info'];
                }
            }
        }

        return $result;
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

        // If a WHERE value is specified
        if (!empty($lookup_info["where_"])) {
            if (explode(',', $lookup_info['where_value']) > 1) {
                $sql .= " WHERE {$lookup_info['where_']} IN ({$lookup_info['where_value']})";
            } else {
                $sql .= " WHERE {$lookup_info['where_']} = {$lookup_info['where_value']}";
            }

        }
        // Execute the query
        if ($mode === 'one') {
            $result = $this->crud->selectOne(sql: $sql, params: []);//, fetch_mode: \PDO::FETCH_ASSOC);
        } else if ($mode === 'many') {
            $result = $this->crud->selectMany(sql: $sql, params: []);
        } else {
            throw new InvalidArgumentException("mode = {$mode} is not a valid input parameter for fetchQueryDefinitionResult");
        }
                

        return $result;
    }

    public function fetchDialogueAttributesByElementId($element_id)
    {
        $sql = "SELECT  d_w.`data-bs-toggle`,
                        d_w.`data-bs-target`
                    FROM dialogue_window as d_w
                    WHERE element_info_id=:element_id";
        $params = ['element_id' => $element_id];
        $result = $this->crud->selectMany(sql: $sql, params: $params,fetch_mode:\PDO::FETCH_ASSOC);
        return $result[0];

    }
}


