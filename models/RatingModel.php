<?php
$user = $_ENV["USERDOMAIN"];
switch ($user) {
        case "DANNY":
                include_once "../config/danny.php";
                break;
        case "":
                break;
        case "":
                break;
}
require_once "Crud.php";
require_once "BaseModel.php";

class RatingModel extends BaseModel
{

    public function fetchAvgRating($article_id)
    {
        $sql = "SELECT `AVGrating` as AVGrating FROM v_article_avg_rating 
                WHERE id=:article_id";
        $params = ["article_id" => $article_id];
        $stmt = $this->crudTemp-> db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveRating(int $user_id,int $article_id, int $rating)
    {
        $sql = "INSERT INTO rating (user_id,article_id, rating) 
                VALUES (:user_id,:article_id,:rating)";
        $params = ["user_id"=> $user_id,"article_id"=> $article_id,"rating"=> $rating];
        $stmt = $this->crudTemp->db->prepare($sql);
        try {
        $stmt->execute($params);
        return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }
}


$test = new RatingModel();
print_r($test->saveRating(1,1,3));
