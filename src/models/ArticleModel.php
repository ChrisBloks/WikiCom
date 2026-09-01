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
        $result = $this->crud->selectOne(sql: $sql, params: $params);
        if ($result === false) {
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
        $result = $this->crud->selectMany(sql: $sql, params: $params);
        if (empty($result)) {
            $result = false;
        }
        return $result;
    }

    /**
     * Get all tags from the database associated with a given article
     * @param int $article_id
     * @return array|false array of tags (strings) or false on failure
     */
    public function fetchArticleTags(int $article_id): array|false
    {
        $sql = "SELECT `name`
                FROM
                    wiki_tag
                JOIN wiki_article_to_tag ON wiki_article_to_tag.wiki_tag_id = wiki_tag.id
                WHERE article_id =:article_id";
        $params = ["article_id" => $article_id];
        return $this->crud->selectMany($sql, $params, \PDO::FETCH_COLUMN);
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
        $sortBy = array_key_exists($sortBy, self::$sort_values) ? self::$sort_values[$sortBy] : 'lastEdit';


        $base_select_clause = "SELECT DISTINCT 
                                article.id,
                                article.title, 
                                article.summary, 
                                article.lastEdit AS lastEdit";

        $extra_select_clause = ", COALESCE(vr.AVGrating, 0) AS rating" .
            (!empty($author_ids) ? // (optional append) If authors are specified
                " ,user.name as author" : // get the authors
                ""); // get average rating

                $from_clause = " FROM wiki_article as article";

        // Building the JOIN and WHERE clauses
        // For storing mapping of placeholder to variables for PREPARED statement
        $params = [];
        [$join_clause, $where_clause] = (function ($author_ids, $tag_ids, &$params) {
            // Iteratively add neccessary parts to the join and where clauses
            $join_clause = " JOIN v_article_avg_rating as vr ON vr.id = article.id";
            $where_statements = [];

            // check if any tags are given and build IN clause
            if (!empty($tag_ids)) {
                $join_clause .=  ' JOIN wiki_article_to_tag att ON att.article_id = article.id' .
                    ' JOIN wiki_tag ON wiki_tag.id = att.wiki_tag_id';
                [$in_clause, $placeholder_mapping] = $this->buildInClause(reference: 'att.wiki_tag_id', values: $tag_ids, prefix: 'tag');
                $where_statements[] = $in_clause;
                $params = array_merge($params, $placeholder_mapping);
            }
            // check if any authors are given and build IN clause
            if (!empty($author_ids)) {
                $join_clause .= ' JOIN user ON user.id = article.user_id ';
                [$in_clause, $placeholder_mapping] = $this->buildInClause(reference: 'article.user_id', values: $author_ids, prefix: 'user');
                $where_statements[] = $in_clause;
                $params = array_merge($params, $placeholder_mapping);
            }

            // If where statements are added, return the where clause, otherwise return nothing
            $where_clause = (!empty($where_statements) ? ' WHERE ' . implode(" AND ", $where_statements) : '');
            return [$join_clause, $where_clause];
        })($author_ids, $tag_ids, $params);

        $order_by_clause = ' ORDER BY ' . $sortBy . ' DESC;';

        // Connect the clauses
        $sql =  $base_select_clause .
            $extra_select_clause .
            $from_clause .
            $join_clause .
            $where_clause .
            $order_by_clause;

        return $this->crud->selectMany(sql: $sql, params: $params);
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


    /**
     * Saves new article to the database given the article info
     * @param string $article_title
     * @param string $article_summary
     * @param string $article_codeBlock
     * @param string $imgFileName
     * @param int $user_id id of the author.
     * @return int|false
     */
    public function saveNewArticleInfo(string $article_title, string $article_summary, string $article_codeBlock, string $imgFileName, int $user_id): int|false
    {
        $sql = "INSERT INTO wiki_article (title, summary, codeBlock, imgFileName, user_id, lastEdit)
                    VALUES (:title,:summary,:codeBlock,:imgFileName,:user_id,:lastEdit)";
        $params = [
            ':title' => $article_title,
            ':summary' => $article_summary,
            ':codeBlock' => $article_codeBlock,
            ':imgFileName' => $imgFileName,
            ':user_id' => $user_id,
            ':lastEdit' => date(format: 'Y-m-d'),
        ];
        $result = $this->crud->doInsert(sql: $sql, params: $params);
        if ($result === false) {
            $this->logError(message: "Failed to save article to the database.");
        }
        return $result;
    }

    /**
     * Updates an existing article
     * @param int $article_id 
     * @param string $article_title
     * @param string $article_summary
     * @param string $article_codeBlock
     * @param string $imgFileName
     * @param int $user_id id of the author
     * @return bool
     */
    public function saveExistingArticleInfo(int $article_id, string $article_title, string $article_summary, string $article_codeBlock, string $imgFileName, int $user_id): int|false
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

        return $this->crud->doUpdate(sql: $sql, params: $params);
    }


    /**
     * Checks if a tag already exists.
     * @param string $tag_name
     * @return bool True if $tag_name matches an entry in the database, false otherwise.
     */
    public function checkTagExists(string $tag_name): bool
    {
        $sql = "SELECT name FROM tag 
                    WHERE name=:tag_name";
        $params = ["tag_name" => $tag_name];
        $result = $this->crud->selectOne(sql: $sql, params: $params);
        return $result;
    }

    /**
     * Checks if a title already exists.
     * @param string $title_name
     * @return bool True if $title_name matches an entry in the database, false otherwise.
     */
    public function checkTitleExists(string $title_name): bool
    {
        $sql = "SELECT title FROM wiki_article 
                    WHERE title=:title_name";
        $params = ["title_name" => $title_name];
        $result = $this->crud->selectOne(sql: $sql, params: $params);
        return !empty($result);
    }

    /**
     * Inserts a new tag into the database
     * @param string $tag_name
     * @return int|false database id of new tag or false on failure
     */
    public function addNewTag(string $tag_name): int|false
    {
        $sql = "INSERT INTO wiki_tag (name) 
                    VALUES (:tag_name)";
        $params = ["tag_name" => $tag_name];
        return $this->crud->doInsert(sql: $sql, params: $params);
    }

    /**
     * Adds a bridge-table entry between a given article and tag
     *
     * @param int $article_id
     * @param int $tag_id
     * @return int|false New bridge-table row id or false on failure
     */
    public function addTagToArticle(int $article_id, int $tag_id): int|false
    {
        $sql = "INSERT INTO wiki_article_to_tag (article_id, wiki_tag_id) 
                    VALUES (:article_id,:tag_id)";
        $params = ["article_id" => $article_id, "tag_id" => $tag_id];
        return $this->crud->doInsert(sql: $sql, params: $params);
    }

    /**
     * Removes all tag associations for a given article
     *
     * @param int $article_id
     * @return bool True on success, false on failure
     */
    public function removeTagsFromArticle(int $article_id): bool
    {
        $sql = "DELETE FROM wiki_article_to_tag WHERE `wiki_article_to_tag`.`article_id` = :article_id";
        $params = ["article_id" => $article_id];
        return $this->crud->doDelete(sql: $sql, params: $params);
    }
}
