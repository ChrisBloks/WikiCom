<?php
abstract class ModelSelector
{
    protected static $modellist = [];
    protected static $modelinfo = ["Article" => "getArticleModel",
                                   "Form" => "getFormModel",
                                   "Website" => "getWebsiteInfoModel",
                                   "Rating" => "getRatingModel",              
                                   "User" => "getUserInfoModel"];

    // getLoginModel
    public static function __callStatic($method, $args)
    {
        $model = substr($method, 3);
        require_once "Model/$model.php";
        return self::initializeModel($model);
    }
    public static function callModel($page)
    {
        if (array_key_exists($page, self::$modelinfo));
        {
            $function = self::$modelinfo[$page];
            return self::$function($page);
        }
        
    }
    public static function initializeModel($model)
    {
        if (isset(self::$modellist[$model]) === false) 
        {
            self::$modellist[$model] = new $model();
        }
        return self::$modellist[$model];
    }
}