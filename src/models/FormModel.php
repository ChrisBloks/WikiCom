<?php
/* FormModel
 *  Danny
 *  08/2026
 *  FormModel class gives al the methods needed to pull information from database about forms
 */

namespace Wiki\models;

use Wiki\tools\utils\HtmlUtils;

class FormModel extends BaseModel
{
    /**
     * Gets the necessary field information for a given page.
     * Some fields require an extra sub array as information.
     * Therefore, if 'lookup_id' exists within the result of the first query a second query will be run.
     * @param string $page_name
     * @param string $id
     * @return array|false
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
                WHERE wi.name = :page
                ORDER BY fi.display_order;";
        $params = ["page" => $page_name];
        $result = $this->crud->selectMany($sql, $params);

        if (empty($result)) {
            $this->logError("Page has no Form");
            return false;
        }
        foreach ($result as &$field_info) {
            if (isset($field_info["id"])) {
                $field_sub_info = $this->fetchLookupInfo(field_info: $field_info, parent_id: $id);
                $field_info = array_merge($field_info, $field_sub_info);
            }
        }
        unset($field_info);
        return $result;
    }

    /**
     * Get the form information of a given page.
     * Returns a value for 'action', 'method', 'submit_caption', 'enctype', and 'display_class'
     * @param string $page_name
     * @return array ['action, 'method', 'submit_caption', 'encype', 'display_class']
     */
    public function fetchFormInfo(string $page_name): array|false
    {
        $sql = "SELECT DISTINCT fo.action, 
                                fo.method, 
                                fo.submit_caption,
                                fo.enctype,
                                fo.display_class
                FROM form_info fo
                JOIN website_info wi ON fo.website_info_id = wi.id
                WHERE wi.name = :page";
        $params = ["page" => $page_name];
        $result = $this->crud->selectMany(sql: $sql, params: $params);

        // If the query was succesful, extract the first row
        if ($result !== false) {
            $result = $result[0];
        }

        return $result;
    }

    /*
     * Method that builds and executes a query that relies on information gotten from the previously ran query
     *
     * @params $value = the information from the previous query given to this method to build the query
     *         $id = id given for certain queries that need specific id example: finding the tag for a certain article needs article_id
     */

    // TODO refactor
    public function fetchLookupInfo1(array &$field_info, string $id): void
    {
        // If a table name is given 
        if (!empty($field_info["table_name"])) {
            $sql = "SELECT {$field_info["display_names"]} FROM {$field_info['table_name']}";

            if (!empty($field_info["bridge_table"])) {
                [$main, $bridge] = explode(",", $field_info["bridgevalues"]);
                if (!empty($field_info["left_on"])) {
                    $sql .= "LEFT JOIN {$field_info["bridge_table"]} ON {$main} = {$bridge} AND {$field_info["left_on"]} = {$id} ";
                } else {
                    $sql .= "JOIN {$field_info["bridge_table"]} ON {$main} = {$bridge}";
                }
            }
            if (!empty($field_info["value"])) {
                $sql .= " WHERE {$field_info['value']} = {$id}";
            }

            $sql .= " ORDER BY {$field_info['order_by']}";


            $result = $this->crud->selectMany($sql, NULL, \PDO::FETCH_ASSOC);

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


    /**
     * Find for a given container field the container names/values.
     * 
     * ```
     * INPUT: 
     * $field_info [array]: 
     *          [
     *          'source_table' => string, // Table to SELECT rows from 
     *          'column_names' => string, // SHOULD BE [source_table.id, options_col, values_col]
     *          'order_by' => string, // Equal to name of column on which to sort
     *          'where_value' => string, // (optional) Value to match against $id (useless???)
     *          'bridge_table' => string, // (optional) name of bridge table for JOIN
     *          'bridge_values' => string, // (optonal) 'bridge_table.column,table_name.column' column names to use for bridge table
     *          'left_join_on' => string // (optional) if specified, regular JOIN turns into a LEFT JOIN on 'left_join_on'
     *          ];
     * $parent_id [string] // The id of object (article, user, ... etc.) that contains this container field
     * 
     * OUTPUT [array]: // Array of subfield values
     *          [
     *          options_col => 
     *              [ 
     *              'id1' => x,
     *              'id2' => y,
     *              ...
     *              ]
     *          values_col =>
     *              [ 
     *              'id1' => a,
     *              'id2' => b,
     *              ...
     *              ]
     *          ];
     * 
     * EXAMPLE INPUT:
     * $field_info [array]:
     *          [
     *          'source_table' => 'wiki_tag', // Source table
     *          'column_names' => 'id,name,!isnull(article_id) as marked',
     *          'order_by' => 'wiki_tag.name', 
     *          'where_value' => '',
     *          'bridge_table' => 'wiki_article_to_tag', // Getting an article's tags requires a bridge table
     *          'bridge_values' => 'wiki_article_to_tag.wiki_tag_id,wiki_tag.id', // Link bridge table to source table.
     *          'left_join_on' => 'wiki_article_to_tag.article_id' 
     *              // LEFT JOIN bridge_table ON 
     *              //      'wiki_article_to_tag.wiki_tag_id' = 'wiki_tag.id' AND
     *              //      'wiki_article_to_tag.article_id' = 'parent_id'
     *          ];
     * $parent_id [string]: '3' // Article 3
     * 
     * OUTPUT: [array]: 
     *          [
     *          options => //name 
     *              [
     *              '1' => 'tag1',
     *              '2' => 'tag42',
     *              ...
     *              ],
     *          values => //marked
     *              [
     *              '1' => true,
     *              '2' => false,
     *              ...
     *              ],
     *          ]
     * 
     * ```
     * 'column_names' Should specify the container name and value.
     * 'id' should specifiy in which article/user/page the container field lives
     *
     * @param array $field_info see INPUT
     * @param string $parent_id id of the parent object holding this container element
     * @return array see OUTPUT
     */
    public function fetchLookupInfo(array $field_info, string $parent_id): array
    {
        // Basic SQL start
        $sql = "SELECT
                    {$field_info['column_names']}
                FROM
                    {$field_info['source_table']}
                ";

        // If a bridge table is required
        if (!empty($field_info["bridge_table"])) {
            [$bridge_table_column, $source_table_column] = explode(",", $field_info["bridgevalues"]);

            $join_clause =  "JOIN {$field_info["bridge_table"]} ON {$bridge_table_column} = {$source_table_column}";
            // If a LEFT JOIN is required
            if (!empty($field_info['left_join_on'])) {
                $join_clause = "LEFT" . $join_clause . " AND {$field_info["left_join_on"]} = {$parent_id}";
            }
            $sql .= $join_clause;
        }

        // If a WHERE value is specified
        if (!empty($field_info["where_value"])) {
            $sql .= " WHERE {$field_info['where_value']} = {$parent_id}";
        }

        // Always add an ORDER BY clause
        $sql .= " ORDER BY {$field_info['order_by']}";
        

        // Execute the query
        $result = $this->crud->selectMany(sql: $sql, params: [], fetch_mode: \PDO::FETCH_UNIQUE|\PDO::FETCH_ASSOC);

        // format result
        $subcontainer_info = [];
        // Loop over table rows indexed by id
        foreach ($result as $id => $row){
            // Fill with [id => value]
            foreach($row as $column_name => $value){
                $column_name = isset($subcontainer_info['options'][$id]) ? 'value' :'options';
                $subcontainer_info[$column_name][$id] = $value; 
            }
        }

        //         // format result
        // $subcontainer_info = [];
        // $col_name_map = array_combine(
        //         explode(',', $field_info['column_names']),
        //         ['options', 'values']);
        // // Loop over table rows indexed by id
        // foreach ($result as $id => $row){
        //     // Fill with [id => value]
        //     foreach($row as $column_name => $value){
        //         $subcontainer_info[$col_name_map[$column_name]][$id] = $value; 
        //     }
        // }


        return $subcontainer_info;
        
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


