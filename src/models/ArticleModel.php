<?php
/* ArticleModel
*  Danny
*  08/2026
*  ArticleModel class gives al the methods needed to pull or insert article information from database
*/

namespace Wiki\models;

use Wiki\models\BaseModel;
use Wiki\tools\utils\HtmlUtils;

/**
 * Model class for fetching and saving article-page info from and to the database.
 */
class ArticleModel extends BaseModel
{

    /**
     * Defines a static mapping of strings to ORDER BY values for SQL queries.
     * @var array
     */
    private static $sort_values = [
            "rating" => "AVGrating",
            "AVGrating" => "AVGrating",
            "datum" => "article.lastEdit",
            "date" => "article.lastEdit"
    ];


    /**
     * Fetches an article from the database with given id
     * @param int $article_id
     * @return array|false a single article of form [article.title, user.name, article.summary, article.codeblock, article.imgFileName, article.lateEdit]
     */
    public function fetchArticleById(int $article_id): array|false
    {
            $sql = "SELECT  article.title,
                            user.name,
                            article.summary,
                            article.codeBlock,
                            article.imgFileName,
                            article.lastEdit 
                    FROM wiki_article as article
                    JOIN user ON article.user_id=user.id 
                    WHERE article.id=:article_id";
            $params = ['article_id' => $article_id];
            $result = $this->crudTemp->selectOne($sql, $params);
            if (empty($result)) {
                    $result = [];
            }
            return $result;
    }

    /**
     * Fetches an article with the given user id
     * @param int $user_id
     * @return array|false a single article of form [id, title, lastEdit]
     */
    public function fetchArticleByUserId(int $user_id): array|false
    {
            $sql = "SELECT  article.id,
                            article.title, 
                            article.lastEdit 
                    FROM wiki_article as article
                    WHERE user_id=:user_id";
            $params = ['user_id' => $user_id];
            $result = $this->crudTemp->selectMany($sql, $params);
            if (empty($result)) {
                    $result = false;
            }
            return $result;
    }

    /**
     * Fetches articles based on array of filters.
     * Dynamically builds an SQL query.
     * Articles are returned if there is (ANY match on $user_ids) AND (ANY match on $tag_ids).
     * @param array $author_ids array of ints.
     * @param array $tag_ids array of ints.
     * @param string $sortBy defines contents of the SORT BY clause.
     * @return array|false Array of articles where each article has form [id, title, summary, lastEdit]
     */
    public function fetchArticleBySearch(array $author_ids = [], array $tag_ids = [], string $sortBy = ""): array|false
    {
        // Check if sortBy is a valid sorting method
        $sortBy = array_key_exists($sortBy, self::$sort_values) ? self::$sort_values[$sortBy] : 'article.lastEdit';

        $base_select_clause = "SELECT DISTINCT 
                                article.id,
                                article.title, 
                                article.summary, 
                                article.lastEdit";

        $extra_select_clause = ", COALESCE(vr.AVGrating, 0) AS rating" .
                                (!empty($author_ids) ? // If authors are specified
                                    " ,user.name as author" : // get the authors
                                    ""); // get average rating

        $from_clause = " FROM wiki_article as article";

        // For storing mapping of placeholder to variables for PREPARED statement
        $params = [];
        [$join_clause, $where_clause] = (function($author_ids, $tag_ids, &$params){
                // Iteratively add neccessary parts to the join and where clauses
                $join_clause = " JOIN v_article_avg_rating as vr ON vr.id = article.id";
                $where_statements = [];
                
                // check if any tags are given and build IN clause
                if (!empty($tag_ids)){
                    $join_clause .=  ' JOIN wiki_article_to_tag att ON att.article_id = article.id' .
                                        ' JOIN wiki_tag ON wiki_tag.id = att.wiki_tag_id';
                    [$in_clause, $placeholder_mapping] = $this->buildInClause('att.wiki_tag_id', $tag_ids, 'tag');
                    $where_statements[] = $in_clause;
                    $params = array_merge($params, $placeholder_mapping);
                }
                // check if any authors are given and build IN clause
                if (!empty($author_ids)){
                    $join_clause .= ' JOIN user ON user.id = article.user_id ';
                    [$in_clause, $placeholder_mapping] = $this->buildInClause('article.user_id', $author_ids, 'user');
                    $where_statements[] = $in_clause;
                    $params = array_merge($params, $placeholder_mapping);
                }

                // If where statements are added, return the where clause, otherwise return nothing
                $where_clause = (!empty($where_statements) ? ' WHERE ' . implode(" AND ", $where_statements) : '');
                return [$join_clause, $where_clause];
        })($author_ids, $tag_ids, $params);

        $order_by_clause = ' ORDER BY ' . $sortBy . ';';

        // Connect the clauses
        $sql =  $base_select_clause .
                $extra_select_clause .
                $from_clause . 
                $join_clause . 
                $where_clause . 
                $order_by_clause;

        return $this->crudTemp->selectMany($sql, $params);
    }

    /**
     * Dynamically constructs a IN clause for a PREPARED SQL query.
     * 
     * ```
     * EXAMPLE:
     * Input: inClause("article.user_id", [1 => 'a', 2 => 'b'], "user")
     * Output: [$result, $params] where
     *      $result: "article.user_id IN (:user_1, :user_2)" and
     *      $params: ['user_1' => 'a', 'user_2'=> 'b']
     * ```
     * @param string $reference Column to compare against
     * @param array $values Values to match with
     * @param string $prefix String to bind variables to for the prepared statement
     * @return array [$result, $params] see example
     */
    private function buildInClause(string $reference, array $values, string $prefix): array
    {
            $placeholders = []; // Will contain strings for binding variables
            $params = []; // Will contain a mapping of placeholders to variables

            // Add each value to placeholders and params with correct formatting
            foreach (array_values($values) as $i => $value) {
                    $key = $prefix . '_' . $i;
                    $placeholders[] = ':' . $key;
                    $params[$key] = $value;
            }

            // Generate IN clause 
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
            $result = $this->crudTemp->doInsert($sql, $params);
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
            } catch (\PDOException $e) {
                    // $this->logError($e->getMessage());
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
    * Method that checks if title already exists. If it does not exist give true
    *
    * @param title name
    */

    public function checkTitle($title_name)
    {
            $sql = "SELECT title FROM wiki_article 
                    WHERE title=:title_name";
            $params = ["title_name" => $title_name];
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
            } catch (\PDOException $e) {
                    // $this->logError($e->getMessage());
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
            } catch (\PDOException $e) {
                    // $this->logError($e->getMessage());
                    return false;
            }
    }
}
