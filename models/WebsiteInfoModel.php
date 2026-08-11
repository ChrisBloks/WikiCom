<?php
require_once "Crud.php";
require_once "BaseModel.php";

class WebsiteInfoModel extends BaseModel
{

    public function getBodyText($page_value, $user_id = '')
    {
        if ($page_value == 'home') {
            $sql = "SELECT bodytext FROM website_info WHERE name=:page";
            $params = ["page" => $page_value];
            $stmt = $this->crudTemp-> db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } elseif ( $page_value == "about") {
            $sql = "SELECT name,description,imgFileName FROM user WHERE id=:userid";
            $params = ["userid" => $user_id];
            $stmt = $this->crudTemp -> db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        else {
            return false;
        }

    }
}

$test = new WebsiteInfoModel();
print_r($test->getBodyText("home",'Danny'));
print_r($test->getBodyText("about",1));