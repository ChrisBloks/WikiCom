<?php

namespace Wiki\api\handlers;

use Wiki\tools\utils\Utils,
CONFIG;
use Wiki\tools\traits\tErrorMessageCollector;
use Wiki\tools\utils\HtmlUtils;
use Wiki\models\ModelSelector;

class APIHandler
{
    //properties
    protected string $action;
    protected $handler;
    protected array $routing;
    protected string $api_key;

    use tErrorMessageCollector;

    public function __construct()
    {
        $this->routing = [
            'getAllUsers' => 'JsonGetAllUsers',
            'getAllArticles' => 'JsonGetAllArticles',
            'getAllTags' => 'JsonGetAllTags',
            'getArticlesByUser' => 'JsonGetArticlesByUser',
            'getArticlesByTag' => 'JsonGetArticlesByTag',
            'getArticlesByUserTag' => 'JsonGetArticlesByUserTag',
            'getArticleById' => 'JsonGetArticleById',
            'getAllArticlesXML' => 'XmlGetAllArticles',
        ];
    }

    //==============================================================================
    public function handleRequest($request)
    {
        // try {
        // start buffer
        ob_start();
        // get API action
        $this->action = $request['action'];
        //check api key
        $this->api_key = $request['api_key'] ?? '';
        $api_check = ModelSelector::getUserInfoModel()->checkAPIKey($this->api_key);

        if ($api_check) {
            // create associated handler and execute it
            $this->createHandler($request);

            $this->executeHandler();
        } else {
            header("Content-type: application/json");
            echo 'Api key is not correct';
        }

        ob_end_flush();
        // } catch (\Throwable $t) {
        //     ob_end_clean();
        //     $this->logError($t);
        // } catch (\Exception $e) {
        //     ob_end_clean();
        //     $this->logError($e);
        // }
    }
    //==============================================================================
    private function createHandler($request)
    {

        if (array_key_exists($this->action, $this->routing)) {

            $class = $this->routing[$this->action];

            $class_path = CONFIG::APICLASSPATH . $class;
            $this->handler = new $class_path($request);
        } else {
            throw new \Exception("Oops api call didnt work");
        }
    }
    //==============================================================================
    private function executeHandler()
    {
        if ($this->handler) {
            $api_check = ModelSelector::getUserInfoModel()->addCountAPIKey($this->api_key);
            if ($api_check) {
                return $this->handler->execute();
            } else {
                header("Content-type: application/json");
                echo 'Api request failed';
            }
        }
    }

}