<?php

namespace Wiki\controllers;

use Wiki\controllers\factories\ElementFactory;
use Wiki\dataObjects\ElementInfo;
use Wiki\models\ModelSelector;
use Wiki\tools\utils\HtmlUtils;

class ElementHandler {

    protected array $article_info = []; // list of [title, name, summary, codeBlock, imgFileName, lastEdit, user_id, rating, n_ratings]
    protected array $fetched_random_articles = []; // list of [id]
    protected array $user_info = []; // list of [id, name, password, email, imgFileName, description]
    protected array $response;

    public function __construct(array $response){
        HtmlUtils::dump('response', $response);
        $this->response = $response;
    }

    public function addDataToElementList(array &$elements){
        
        foreach($elements as $element_info){

            if(isset($element_info['sub_fields'])){
                $this->addDataToElementList($element_info['sub_fields']);
            }

            if(isset($element_info['response_variables'])){
                $this->addDataToElement($element_info);
            }
        }
    }

    // TODO: Currently a problem that variables get slotted into seemingly random locations (i.e. into 'title' sometimes, into 'bodytext' other times)
    public function addDataToElement(ElementInfo &$element_info){
        $response_variables = &$element_info['response_variables'];
        
        foreach($response_variables as $key => $var){
            switch ($var){
                case '$random_article':
                    $random_article_info = ModelSelector::getArticleModel()->fetchRandomArticle($this->fetched_random_articles);
                    $this->fetched_random_articles[] = $random_article_info['id'];
                    $element_info['article_info'] = $random_article_info;
                    break;
                case '$author_name':
                    $about_info = $this->getUserInfo($this->response['aboutID']);
                    $element_info['title'] = $about_info['name'];
                    break;
                case '$author_text':
                    $about_info = $this->getUserInfo($this->response['aboutID']);
                    $element_info['bodytext'] = $about_info['description'];
                    break;
                case '$author_image':
                    $about_info = $this->getUserInfo($this->response['aboutID']);
                    $element_info['image'] = \CONFIG::AUTHORIMGPATH . $about_info['imgFileName'];
                    break;
                case '$article_info':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $element_info['article_info'] = $article_info;
                    break;
                case '$user_email':
                    $user_info = $this->getUserInfo($this->response['userID']);
                    $element_info['title'] = $user_info['email'];
                    break;
                case '$user_img':
                    $user_info = $this->getUserInfo($this->response['userID']);
                    $element_info['image'] = \CONFIG::AUTHORIMGPATH . $user_info['imgFileName'];
                    break;
                case '$user_name':
                    $user_info = $this->getUserInfo($this->response['userID']);
                    $element_info['title'] = $user_info['name'];
                    break;
                default:
                    $response_variables[$key] = "{$var} not recognized!";
                    break;
            }
        }
        // HtmlUtils::dump('Element', $element_info);
    }

    // For caching user info
    private function getUserInfo(int $user_id){
        if(!isset($this->user_info[$user_id])){
            $this->user_info[$user_id] = ModelSelector::getUserInfoModel()->fetchUserInfoById($user_id);
        }
        return $this->user_info[$user_id];
    }

    //For checking caching article info, if this article has already been fetched, use the cache
    private function getArticleInfo(int $article_id){
        if (!isset($this->article_info[$article_id])){
            $article_info_with_tags =  ModelSelector::getArticleModel()->fetchArticleById($article_id, get_tags: True);
            $article_info = $article_info_with_tags[0];
            $article_info['tags'] = $article_info_with_tags[1]; // put tags in correct place
            $this->article_info[$article_id] = $article_info;
           
        }
        return $this->article_info[$article_id];
    }
}