<?php
abstract class BaseController implements iController
{
    protected static bool $posted;
    protected static bool $async;

    public function __construct(){
        self::$posted = ($_SERVER['REQUEST_METHOD']=== 'POST');
        self::$async = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    abstract public function handleRequest() : void;

}