<?php

namespace Wiki\api\handlers;

use Wiki\tools\utils\Utils,
    CONFIG;
use Wiki\tools\traits\tErrorMessageCollector;

class NewApiHandler
{
    //properties
    protected string $action;
    protected $handler;
    protected array $routing;

    use tErrorMessageCollector;

    public function __construct()
    {
        $this->routing = [
            'getAllUsers'           => 'JsonGetAllUsers',
            'getAllArticles'        => 'JsonGetAllArticles',
            'getAllTags'            => 'JsonGetAllTags',
            'getArticlesByUser'     => 'JsonGetArticlesByUser',
            'getArticlesByTag'      => 'JsonGetArticlesByTag',
            'getArticlesByUserTag'  => 'JsonGetArticlesByUserTag',
            'getArticleById'        => 'JsonGetArticleById',
        ];
    }

    //==============================================================================
    public function handleRequest()
    {
        try {
            // start buffer
            ob_start();
            // get API action
            $this->action = Utils::getRequestVar('action', false, '');
            // create associated handler and execute it
            $this->createHandler();

            $this->executeHandler();
            ob_end_flush();
        }
        catch (\Throwable $t) {
            ob_end_clean();
            $this->logError($t);
        } catch (\Exception $e) {
            ob_end_clean();
            $this->logError($e);
        }
    }
    //==============================================================================
    private function createHandler()
    {
        if (array_key_exists($this->action, $this->routing)) {
            $class = $this->routing[$this->action];
            require_once CONFIG::APICLASSPATH . $class . ".php";
            $this->handler = new $class(
                action: $this->action
            );
        } else {
            throw new \Exception("Oops api call didnt work");
        }
    }
    //==============================================================================
    private function executeHandler()
    {
        if ($this->handler) {
            $this->handler->execute();
        }
    }

}