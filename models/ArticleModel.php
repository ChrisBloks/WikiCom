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

class ArticleModel extends BaseModel
{

        public function fetchArticleById($article_id)
        {
                $sql = "SELECT * FROM article 
                        WHERE id=:article_id";
                $params = ['article_id' => $article_id];
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function fetchArticleByUserId($user_id)
        {
                $sql = "SELECT * FROM user 
                        WHERE id=:user_id";
                $params = ['user_id' => $user_id];
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function fetchArticleBySearch($user_ids = [], $tag_ids = [], $sortBy = "")
        {
                $sql_start = "SELECT DISTINCT article.title, article.summary, article.lastEdit";
                $sql_body = "";
                $params = [];
                $wherestrings = [];

                if (!empty($tag_ids)) {
                        $sql_body .= " JOIN article_to_tag att ON att.article_id = article.id ";
                        $sql_body .= " JOIN tag ON tag.id = att.tag_id  ";
                        $placeholder = str_repeat('?,', count($tag_ids) - 1) . '?';
                        $wherestrings[] = "att.tag_id IN ($placeholder)";
                        $params = array_merge($params, $tag_ids);
                }

                if (!empty($user_ids)) {
                        $sql_start .= " ,user.name as author ";
                        $sql_body .= " JOIN user ON user.id = article.user_id ";
                        $placeholder = str_repeat('?,', count($user_ids) - 1) . '?';
                        $wherestrings[] = "article.user_id IN ($placeholder)";
                        $params = array_merge($params, $user_ids);
                }

                $sql_start .= " ,COALESCE(vr.AVGrating,0) as AVrating ";
                $sql_body .= " JOIN v_article_avg_rating as vr ON vr.id = article.id ";

                if (!empty($wherestrings)) {
                        $sql_body .= " WHERE " . implode(" AND ", $wherestrings);
                }

                $sql_start .= " FROM article ";
                $sql_end = " ORDER BY $sortBy; ";

                $sql = $sql_start . $sql_body . $sql_end;
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        }
        public function saveNewArticleInfo($article_title, $article_summary, $article_codeBlock, $imgFileName, $user_id)
        {
                $sql = "INSERT INTO article (article.title,article.summary,article.codeBlock,article.imgFileName,article.user_id,article.lastEdit)
                        VALUES (:title,:summary,:codeBlock,:imgFileName,:user_id,:lastEdit)";
                $params = [
                        ':title' => $article_title,
                        ':summary' => $article_summary,
                        ':codeBlock' => $article_codeBlock,
                        ':imgFileName' => $imgFileName,
                        ':user_id' => $user_id,
                        ':lastEdit' => date('Y-m-d'),
                ];
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);
                return $this->crudTemp->db->lastInsertId();
        }

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

                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);

                try {
                        $stmt->execute($params);
                        return true;
                } catch (PDOException $e) {
                        return false;
                }
        }

        public function checkTag($tag_name)
        {
                $sql = "SELECT name FROM tag 
                        WHERE name=:tag_name";
                $params = ["tag_name" => $tag_name];
                $stmt = $this->crudTemp->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return empty($result);
        }

        public function addNewTag(string $tag_name)
        {
                $sql = "INSERT INTO tag (name) 
                        VALUES (:tag_name)";
                $params = ["tag_name" => $tag_name];
                $stmt = $this->crudTemp->db->prepare($sql);
                try {
                        $stmt->execute($params);
                        return true;
                } catch (PDOException $e) {
                        return false;
                }
        }

        public function addTagToArticle(int $article_id, int $tag_id)
        {
                $sql = "INSERT INTO article_to_tag (article_id, tag_id) 
                        VALUES (:article_id,:tag_id)";
                $params = ["article_id" => $article_id, "tag_id" => $tag_id];
                $stmt = $this->crudTemp->db->prepare($sql);
                try {
                        $stmt->execute($params);
                        return true;
                } catch (PDOException $e) {
                        return false;
                }
        }
}

// echo exec('whoami');
//     echo get_current_user();

// $test = new ArticleModel();
// //print_r($test->fetchArticleByUserId(2));
// // print_r($test->fetchArticleById(2));
// print_r($test->fetchArticleBySearch(user_ids:[1,2],sortBy:'AVGrating'));





//$test = new ArticleModel();
// $mail = "danny@email12.com";
// if ($test -> checkEmail($mail)) {
//         print_r($test->registerUser("Danny", "Password",$mail,"",""));
// }
//print_r($test->saveExistingArticleInfo(4, "testaaa", "test2ddd", "test3dd", "test.jpg", 1));
// print_r($test->addTagToArticle(1, 5));