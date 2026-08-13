<?php
require_once "Crud.php";
require_once "BaseModel.php";
class FormModel extends BaseModel
{
    public function getFieldInfo($page_value)
    {
        $sql = "SELECT fi.type, fi.name,fi.class,fi.label,fi.options
                FROM field_info fi
                JOIN fields_per_page fpp ON fpp.field_info_id = fi.id
                JOIN website_info wi ON wi.id = fpp.website_info_id
                WHERE wi.name = :page
                ORDER BY fi.display_order";
        $params = ["page" => $page_value];
        $result = $this->crudTemp->selectMany($sql, $params);

        if (empty($result)) {
            $this->logError("Page has no Form");
            return false;
        }

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

    public function getTag()
    {
        $sql = "SELECT id,name FROM tag ORDER BY tag.name";
        $result = $this->crudTemp->selectMany($sql,NULL,PDO::FETCH_KEY_PAIR);
        if (empty($result)) {
            $this->logError("No tags");
            $result = false;
        }
        return $result;
    }

    public function getAuthor()
    {
        $sql = "SELECT id,name FROM user ORDER BY user.name";
        $result = $this->crudTemp->selectMany($sql,NULL,PDO::FETCH_KEY_PAIR);
        if (empty($result)) {
            $this->logError("No authors");
            $result = false;
        }
        return $result;
    }
}
