<?php

namespace Wiki\api\Handlers;

use Wiki\models\ArticleModel;
use Wiki\models\UserInfoModel;
use Wiki\tools\utils\Utils;

class APIHandler
{
    protected string $func;
    protected $handler;
    protected array $routing;

    // routing for classes
    public function __construct(){
        $this->routing = [
            'getAllUsers'       => 'JsonGetAllUsers',
            'getAllArticles'    => 'JsonGetAllArticles',
            'getAllTags'        => 'JsonGetAllTags',
            'getArticlesByUser' => 'JsonGetArticlesByUser',
            'getArticlesByTag'  => 'JsonGetArticlesByTag',
            'getArticlesByUserTag' => 'JsonGetArticlesByUserTag',
            'getArticleById'    => 'JsonGetArticleById',  
        ];
    }

    public function handleRequest(array $request){

        $response = $request;
        switch ($request['action']) {
            case 'getAllUsers':
                $response['result'] = (new UserInfoModel)->fetchAllUsers();
                break;
            case 'getAllArticles':
                $response['result'] = (new ArticleModel)->fetchAllArticles();
                break;
            case 'getAllTags':
                $response['result'] = (new ArticleModel)->fetchAllTags();
                break;
            case 'getArticlesByUser':
                $user_id = Utils::getRequestVar('userID', false, 0, true);
                $response['result'] = (new ArticleModel)->fetchArticlesByUserID($user_id);
                break;
            case 'getArticlesByTag':
                $tag = Utils::getRequestVar('tag', false, 0, true);
                $response['result'] = (new ArticleModel)->fetchArticlesByTag($tag);
                break;
            case 'getArticlesByUserTag':
                $tag = Utils::getRequestVar('tag', false, 0, true);
                $user_id = Utils::getRequestVar('userID', false, 0, true);
                $response['result'] = (new ArticleModel)->fetchArticlesByTagAndUserId($tag, $user_id);
                break;
            case 'getArticleById':
                $article_id = Utils::getRequestVar('articleID', false, 0, true);
                $response['result'] = (new ArticleModel)->fetchArticleById($article_id);
                break;
            default:
                $response['error'] = 'action ' . $request['action'] . ' not recognized in APIHandler!';
                break;
        }

        return $response;
    }
}