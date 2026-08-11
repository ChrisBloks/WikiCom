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
class ArticleModel extends BaseModel{

public function fetchArticleById($article_id){
        $sql = "SELECT * FROM article WHERE id=:article_id";
        $params = ['article_id' => $article_id];
        $stmt = $this->crudTemp-> db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
}

public function fetchArticleBySearch($user_ids=[], $tag_ids=[], $sortBy=[]){
        $sql = "SELECT DISTINCT article.* FROM article";
        $params = [];
        $strings = [];

        if (!empty($tag_ids)) {
                $sql .= " JOIN article_tag ON article_tag.article_id = article.id";
                $placeholder = str_repeat('?,', count($tag_ids) - 1) . '?';
                $strings[] = "article_tag.tag_id IN ($placeholder)";
                $params = array_merge($params, $tag_ids);
        }

        if (!empty($user_ids)) {
                $placeholder = str_repeat('?,', count($user_ids) - 1) . '?';
                $strings[] = "article.user_id IN ($placeholder)";
                $params = array_merge($params, $user_ids);
        }

        if (!empty($strings)) {
                $sql .= " WHERE " . implode(" AND ", $strings);
        }

        $stmt = $this->crudTemp->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

}


$test = new ArticleModel();
print_r($test->fetchArticleBySearch([1,2]));