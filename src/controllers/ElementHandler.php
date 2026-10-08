<?php

namespace Wiki\controllers;

use Wiki\controllers\factories\ElementFactory;
use Wiki\dataObjects\ElementInfo;
use Wiki\models\ModelSelector;
use Wiki\tools\Queue;
use Wiki\tools\utils\HtmlUtils;

class ElementHandler
{

    protected array $article_info = []; // list of [title, name, summary, codeBlock, imgFileName, lastEdit, user_id, rating, n_ratings]
    protected array $fetched_random_articles = []; // list of [id]
    protected array $user_info = []; // list of [id, name, password, email, imgFileName, description]
    protected array $response;

    public function __construct(array $response)
    {
        // HtmlUtils::dump('response', $response);
        $this->response = $response;
    }

    public function addDataToElementList(array &$elements)
    {

        foreach ($elements as $element_info) {

            if (isset($element_info['sub_fields'])) {
                // HtmlUtils::dump('TRIGGERED SUBFIELD', []);
                $this->addDataToElementList($element_info['sub_fields']);
            }

            $element_info['application_data'] = ModelSelector::getElementModel()->fetchElementVariables($element_info['element_id']);
            // HtmlUtils::dump('Element', $element_info);
            if (isset($element_info['application_data']) && !empty($element_info['application_data'])) {
                $this->addDataToElement($element_info);
                // HtmlUtils::dump('Element_info', $element_info);
            }
        }
    }

    // TODO: Currently a problem that variables get slotted into seemingly random locations (i.e. into 'title' sometimes, into 'bodytext' other times)
    public function addDataToElement(ElementInfo &$element_info)
    {
        $application_data = &$element_info['application_data'];

        foreach ($application_data as $key => $var) {
            switch ($var) {
                case '[*ADD_USER_TO_HTML_ID*]':
                    $app_data = $this->response['userID'] . $element_info['html_id'];
                    break;
                case '[*ARTICLE_AUTHOR*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = $article_info['name'];
                    break;
                case '[*ARTICLE_AUTHOR_ID*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = $article_info['user_id'];
                    break;
                case '[*ARTICLE_CODE*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = $article_info['codeBlock'];
                    break;
                case '[*ARTICLE_ID*]':
                    $app_data = $this->response['articleID'];
                    break;
                case '[*ARTICLE_IMG*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = \CONFIG::ARTICLEIMGPATH . $article_info['imgFileName'];
                    break;
                case '[*ARTICLE_INFO*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = $article_info;
                    break;
                case '[*IS_LOGGED_IN*]':
                    $app_data = $this->response['isLoggedIn'];
                    break;
                case '[*ARTICLE_N_RATINGS*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = $article_info['n_ratings'];
                    break;
                case '[*ARTICLE_RATING*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = $article_info['rating'];
                    break;
                case '[*ARTICLE_TAGS*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = $article_info['tags'];
                    break;
                case '[*ARTICLE_TEXT*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = $article_info['summary'];
                    break;
                case '[*ARTICLE_TITLE*]':
                    $article_info = $this->getArticleInfo($this->response['articleID']);
                    $app_data = $article_info['title'];
                    break;
                case '[*AUTHOR_IMAGE*]':
                    $about_info = $this->getUserInfo($this->response['aboutID'] ?? $this->response['author'] ?? -1);
                    $app_data = \CONFIG::AUTHORIMGPATH . $about_info['imgFileName'];
                    break;
                case '[*AUTHOR_NAME*]':
                    $about_info = $this->getUserInfo($this->response['aboutID'] ?? $this->response['author'] ?? -1);
                    $app_data = $about_info['name'];
                    break;
                case '[*AUTHOR_TEXT*]':
                    $about_info = $this->getUserInfo($this->response['aboutID'] ?? $this->response['author'] ?? -1);
                    $app_data = $about_info['description'];
                    break;
                case '[*DIALOGUE_ATTRIBUTES*]':
                    $app_data = ModelSelector::getElementModel()
                        ->fetchDialogueAttributesByElementId($element_info['element_id']);
                    break;
                case '[*RANDOM_ARTICLE*]':
                    $random_article_info = ModelSelector::getArticleModel()->fetchRandomArticle($this->fetched_random_articles);
                    $this->fetched_random_articles[] = $random_article_info['id'];
                    $app_data = $random_article_info;
                    break;
                case '[*USER_ARTICLES*]':
                    $user_articles = ModelSelector::getArticleModel()->fetchArticleByUserId($this->response['userID']);
                    $app_data = $user_articles;
                    break;
                case '[*USER_EMAIL*]':
                    $user_info = $this->getUserInfo($this->response['userID']);
                    $app_data = $user_info['email'];
                    break;
                case '[*USER_ID*]':
                    $app_data = $this->response['userID'];
                    break;
                case '[*USER_IMG*]':
                    $user_info = $this->getUserInfo($this->response['userID']);
                    $app_data = \CONFIG::AUTHORIMGPATH . $user_info['imgFileName'];
                    break;
                case '[*USER_NAME*]':
                    $user_info = $this->getUserInfo($this->response['userID']);
                    $app_data = $user_info['name'];
                    break;
                default:
                    $app_data = "{$var} not recognized!";
                    break;
            }
            // Add the appData to the $element_info

            $arr = &$element_info;
            $keys = new Queue(explode(',', $key));
            $i = 0; // Safety for preventing infinite loop
            while (true && $i < 100) {
                $sub_key = $keys->next();
                if ($keys->isEmpty()) {
                    $arr[$sub_key] = $app_data;
                    break;
                }
                $arr = &$arr[$sub_key];
                $i++;
            }
        }
        // HtmlUtils::dump('Element', $element_info);
    }

    // For caching user info
    private function getUserInfo(int $user_id)
    {
        if (!isset($this->user_info[$user_id])) {
            $this->user_info[$user_id] = ModelSelector::getUserInfoModel()->fetchUserPublicInfoById($user_id);
        }
        return $this->user_info[$user_id];
    }

    //For checking caching article info, if this article has already been fetched, use the cache
    private function getArticleInfo(int $article_id)
    {
        if (!isset($this->article_info[$article_id])) {
            $article_info = ModelSelector::getArticleModel()->fetchArticleById($article_id, get_tags: True);
            $this->article_info[$article_id] = $article_info;

        }
        return $this->article_info[$article_id];
    }
}