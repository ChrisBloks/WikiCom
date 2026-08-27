<?php
/* RatingModel
*  Danny
*  08/2026
*  RatingModel class gives al the methods needed to pull or insert Rating information from database
*/

namespace Wiki\models;

class RatingModel extends BaseModel
{
    /*
    * Method that fetches the avg rating based on a given article id
    *
    * @params $article_id
    */
    public function fetchAvgRating(string $article_id): string|false
    {
        $sql = "SELECT `AVGrating` as AVGrating FROM v_article_avg_rating 
                WHERE id=:article_id";
        $params = ["article_id" => $article_id];
        $result = $this->crud->selectOne($sql, $params);
        if (empty($result)) {
            $this->logError("No average rating for article id");
            $result = false;
        }
        return $result;
    }
    /*
    * Method that saves the rating from a user for an article
    *
    * @params user_id, article_id, rating
    */
    public function saveRating(string $user_id, string $article_id, string $rating): bool
    {
        $sql = "INSERT INTO wiki_rating (user_id,article_id, rating) 
                VALUES (:user_id,:article_id,:rating)
                ON DUPLICATE KEY UPDATE rating=:rating";
        $params = ["user_id" => $user_id, "article_id" => $article_id, "rating" => $rating];
        try {
            $this->crud->prepareAndExecute($sql, $params);
            return true;
        } catch (\PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }
}
