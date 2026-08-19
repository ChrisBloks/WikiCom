<?php
/* ArticleModel
*  Danny
*  08/2026
*  ArticleModel class gives al the methods needed to pull or insert article information from database
*/
require_once "Crud.php";
require_once "BaseModel.php";
class ArticleModel extends BaseModel
{
        /*
        *  @params array for ways to sort using the fetchArticlebySearch method
        */ 
        private static $sort_values = [
                "rating" => "AVGrating",
                "AVGrating" => "AVGrating",
                "datum" => "article.lastEdit",
                "date" => "article.lastEdit"
        ];

        /*
        * Method that fetches the article with a certain id
        *
        * @param int that indicates the article id
        */ 
        public function fetchArticleById($article_id)
        {
                $sql = "SELECT  article.title,
                                user.name,
                                article.summary,
                                article.codeBlock,
                                article.imgFileName,
                                article.lastEdit 
                        FROM article
                        JOIN user ON article.user_id=user.id 
                        WHERE article.id=:article_id";
                $params = ['article_id' => $article_id];
                $result = $this->crudTemp->selectOne($sql, $params);
                if (empty($result)) {
                        $result = false;
                }
                return $result;
        }

        /*
        * Method that fetches the article with the user id
        *
        * @param int that indicates the article id
        */ 
        public function fetchArticleByUserId($user_id)
        {
                $sql = "SELECT  article.id,
                                article.title, 
                                article.lastEdit 
                        FROM article 
                        WHERE user_id=:user_id";
                $params = ['user_id' => $user_id];
                $result = $this->crudTemp->selectMany($sql, $params);
                if (empty($result)) {
                        $result = false;
                }
                return $result;
        }

        /*
        * Method that fetches the article based on arrays of filters
        *
        * @param $user_ids is an array of user ids that the article has to be in
        * @param $tag_ids is an array of tag ids that he article has to be in
        * @param $SortBy a string that indicates how the results should be ordered
        */ 
        public function fetchArticleBySearch($user_ids = [], $tag_ids = [], $sortBy = "")
        {
                $sortBy = array_key_exists($sortBy, self::$sort_values) ? self::$sort_values[$sortBy] : 'article.lastEdit';
                [$joins, $where, $extra_query, $params] = $this->buildSearchQuery($user_ids, $tag_ids);

                $sql = "SELECT DISTINCT article.title, article.summary, article.lastEdit"
                        . $extra_query
                        . " FROM article"
                        . $joins
                        . (!empty($where) ? " WHERE " . implode(" AND ", $where) : "")
                        . " ORDER BY " . $sortBy . ";";


                return $this->crudTemp->selectMany($sql, $params);

        }

        /*
        * Method that adds to the search query for fetchArticleBySearch based on user_ids and tag_ids
        *
        * @param $user_ids is an array of user ids that the article has to be in
        * @param $tag_ids is an array of tag ids that he article has to be in
        */ 
        private function buildSearchQuery(array $user_ids, array $tag_ids)
        {
                $joins = " JOIN v_article_avg_rating as vr ON vr.id = article.id";
                $extra_query = ", COALESCE(vr.AVGrating, 0) AS AVGrating";
                $where = [];
                $params = [];

                if (!empty($tag_ids)) {
                        $joins .= " JOIN article_to_tag att ON att.article_id = article.id"
                                . " JOIN tag ON tag.id = att.tag_id  ";
                        [$clause, $tagParams] = $this->inClause('att.tag_id', $tag_ids, 'tag');
                        $where[] = $clause;
                        $params = array_merge($params, $tagParams);
                }

                if (!empty($user_ids)) {
                        $extra_query .= " ,user.name as author";
                        $joins .= " JOIN user ON user.id = article.user_id ";
                        [$clause, $tagParams] = $this->inClause('article.user_id', $user_ids, 'user');
                        $where[] = $clause;
                        $params = array_merge($params, $tagParams);
                }

                return [$joins, $where, $extra_query, $params];
        }

        /*
        * Method that adds the IN statement into the query: example given the inputs [a,b,c]
        * It would build the following string = a in (c_1:c_1) with array [c_1 => b]
        *
        * @param $reference is the string for the before IN statement
        * @param $values is an array that is used to eventually replace the placeholders in the query statements
        * @param $prefix is a string that represents the name given to the placeholder
        */ 
        private function inClause(string $reference, array $values, string $prefix)
        {
                $placeholders = [];
                $params = [];
                foreach (array_values($values) as $i => $value) {
                        $key = $prefix . '_' . $i;
                        $placeholders[] = ':' . $key;
                        $params[$key] = $value;
                }
                $result = $reference . ' IN (' . implode(',', $placeholders) . ')';
                return [$result, $params];
        }


        /*
        * Method that saves article to the database given the article info
        *
        * @params article info + user id
        */ 
        public function saveNewArticleInfo($article_title, $article_summary, $article_codeBlock, $imgFileName, $user_id)
        {
                $sql = "INSERT INTO article (title, summary, codeBlock, imgFileName, user_id, lastEdit)
                        VALUES (:title,:summary,:codeBlock,:imgFileName,:user_id,:lastEdit)";
                $params = [
                        ':title' => $article_title,
                        ':summary' => $article_summary,
                        ':codeBlock' => $article_codeBlock,
                        ':imgFileName' => $imgFileName,
                        ':user_id' => $user_id,
                        ':lastEdit' => date('Y-m-d'),
                ];
                $result = $this->crudTemp->insert($sql, $params);
                if (empty($result)) {
                        $this->logError("Saving article didn't work idk");
                        $result = false;
                }
                return $result;
        }

        /*
        * Method that updates article based on new information
        *
        * @params article info + user id
        */ 

        public function saveExistingArticleInfo($article_id, $article_title, $article_summary, $article_codeBlock, $imgFileName, $user_id)
        {
                $sql = "UPDATE  article
                        SET     title = :title,
                                summary = :summary,
                                codeBlock = :codeBlock,
                                imgFileName = :imgFileName,
                                user_id = :user_id,
                                lastEdit = :lastEdit
                        WHERE   id = :id";
                $params = [
                        ":id" => $article_id,
                        ':title' => $article_title,
                        ':summary' => $article_summary,
                        ':codeBlock' => $article_codeBlock,
                        ':imgFileName' => $imgFileName,
                        ':user_id' => $user_id,
                        ':lastEdit' => date('Y-m-d'),
                ];

                try {
                        $this->crudTemp->prepareAndExecute($sql, $params);
                        return true;
                } catch (PDOException $e) {
                        $this->logError($e->getMessage());
                        return false;
                }
        }

        /*
        * Method that checks if tag already exists. If it does not exist give true
        *
        * @param tag name
        */ 

        public function checkTag($tag_name)
        {
                $sql = "SELECT name FROM tag 
                        WHERE name=:tag_name";
                $params = ["tag_name" => $tag_name];
                $result = $this->crudTemp->selectOne($sql, $params);
                return empty($result);
        }

        /*
        * Method that adds a new tag to the database
        *
        * @param tag name
        */ 
        public function addNewTag(string $tag_name)
        {
                $sql = "INSERT INTO tag (name) 
                        VALUES (:tag_name)";
                $params = ["tag_name" => $tag_name];
                try {
                        $this->crudTemp->prepareAndExecute($sql, $params);
                        return true;
                } catch (PDOException $e) {
                        $this->logError($e->getMessage());
                        return false;
                }
        }

        /*
        * Method that adds tag to article
        *
        * @params article id and tag id
        */ 
        public function addTagToArticle(int $article_id, int $tag_id)
        {
                $sql = "INSERT INTO article_to_tag (article_id, tag_id) 
                        VALUES (:article_id,:tag_id)";
                $params = ["article_id" => $article_id, "tag_id" => $tag_id];
                try {
                        $this->crudTemp->prepareAndExecute($sql, $params);
                        return true;
                } catch (PDOException $e) {
                        $this->logError($e->getMessage());
                        return false;
                }
        }
}

