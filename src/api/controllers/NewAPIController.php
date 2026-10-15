<?php

namespace Wiki\api\controllers;

use Wiki\api\Handlers\APIHandler;
use Wiki\tools\interfaces\iController;
use Wiki\tools\utils\Utils;

class APIController implements iController
{
    private bool $posted;
    private string $action;
    private array $request = [];
    private array $response = [];
    private int $status = 200;

    protected array $routing;

    public function __construct()
    {
        $this->posted = ($_SERVER['REQUEST_METHOD'] === 'POST');
        // Which params each action needs, and how to validate them
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

    public function handleRequest()
    {
        try {
            $this->getRequest();
            $this->validateRequest();
        } catch (\Exception $e) {
            $this->response = ['status' => 'error', 'message' => 'something went wrong'];
        } catch (\Throwable $e) {
            error_log($e);
            $this->status = 500;
            $this->response = ['status' => 'error', 'message' => 'Internal server error'];
        }

        $this->showResponse(); // always runs, for successes and errors alike
    }

    private function getRequest()
    {
        $this->action = Utils::getRequestVar(key: 'action', frompost: $this->posted, default: '');
        $this->request = ['action' => $$this->action];

        // Collect params for each class?

    }

    private function validateRequest()
    {
        $action = $this->request['action'];

        // if action not set, break
        if ($action === '') {
            throw new \Exception('Missing action', 400);
        }

        // create class for the matched enum
        $this->createHandler();
        // execute handler here

        // Request is valid, so fetch the data
        $this->response = [
            'status' => 'ok',
            'data'   => (new APIHandler)->handleRequest($this->request),
        ];
    }

    private function createHandler()
    {
        if (array_key_exists($this->action, $this->routing)) {
            $class = $this->routing[$this->action];
            // call the file of the class that handles that specific action
            require_once CLASSPATH . 'ajax\\' . $class . ".php";
            $this->handler = new $class(
                func: $this->action
            );
        } else {
            throw new \Exception("<h1>OOPS : no action defined for [" . $this->action . "]</h1>");
        }
    }

    private function showResponse()
    {
        $formatter = match (strtolower($_GET['format'] ?? 'json')) {
            // 'xml' => new XmlFormatter(),
            default => new JsonFormatter(),
        };

        http_response_code($this->status);
        header('Content-Type: ' . $formatter->contentType());
        echo $formatter->format($this->response);
    }
}
