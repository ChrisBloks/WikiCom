<?php
require_once "Crud.php";
require_once "BaseModel.php";

class RatingModel extends BaseModel
{

    public function fetchAvgRating($article_id)
    {
        $sql = "SELECT `AVG(r.``rating``)` as AVGrating FROM v_article_avg_rating WHERE id=:article_id";
        $params = ["article_id" => $article_id];
        $stmt = $this->crudTemp-> db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}

$test = new RatingModel();
print_r($test->fetchAvgRating(1));
