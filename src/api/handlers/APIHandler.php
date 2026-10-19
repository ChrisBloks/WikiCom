<?php

namespace Wiki\api\handlers;

use Wiki\tools\utils\Utils,
CONFIG;
use Wiki\tools\traits\tErrorMessageCollector;
use Wiki\tools\utils\HtmlUtils;

class APIHandler
{
    //properties
    protected string $action;
    protected $handler;
    protected array $routing;

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
            // create associated handler and execute it
            $this->createHandler($request);

            $this->executeHandler();
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
            return $this->handler->execute();
        }
    }

}