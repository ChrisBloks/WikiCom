<?php

namespace Wiki\controllers;

use Wiki\models\ArticleModel;
use Wiki\models\UserInfoModel;


class APIHandler
{
    // properties

    public function __construct(){

    }

    public function handleGetAllUsers(){
        return (new UserInfoModel)->fetchAllUsers();
    }

    public function handleGetAllArticles(){
        return (new ArticleModel)->fetchAllArticles();
    }

    public function handleGetAllTags(){
        return (new ArticleModel)->fetchAllTags();
    }

    public function getArticlesByUserID(int $user_id){
        return (new ArticleModel)->fetchArticlesByUserID($user_id);
    }

    public function getArticlesByTag(string $tag){
        return (new ArticleModel)->fetchArticlesByTag($tag);
    }

    public function getArticlesByUserAndTag(string $tag, int $user_id)
    {
        return (new ArticleModel)->fetchArticlesByTagAndUserId($tag, $user_id);
    }


}