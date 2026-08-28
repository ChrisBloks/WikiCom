<?php
/* RatingModel
*  Danny
*  08/2026
*  RatingModel class gives al the methods needed to pull or insert Rating information from database
*/

namespace Wiki\models;

class RatingModel extends BaseModel
{

    /**
     * Fetches average rating of an article based on id
     * @param int $article_id
     * @return float|false
     */
    public function fetchAvgRating(int $article_id): float|false
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

    /**
     * Saves a user's article rating to the database
     * @param int $user_id
     * @param int $article_id
     * @param int $rating
     * @return bool
     */
    public function saveRating(int $user_id, int $article_id, int $rating): bool
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
