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
        return $this->crudTemp->selectOne($sql, $params);
    }

    public function saveRating(int $user_id,int $article_id, int $rating)
    {
        $sql = "INSERT INTO rating (user_id,article_id, rating) 
                VALUES (:user_id,:article_id,:rating)
                ON DUPLICATE KEY UPDATE rating=:rating";
        $params = ["user_id"=> $user_id,"article_id"=> $article_id,"rating"=> $rating];
        try {
        $this->crudTemp->prepareAndExecute($sql,$params);
        return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }
}

