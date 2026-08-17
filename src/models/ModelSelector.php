<?php

require_once "Crud.php";
require_once "BaseModel.php";


abstract class ModelSelector
{
    protected static $modellist = [];
    protected static $modelinfo = ["getArticleModel" => "ArticleModel",
                                   "getFormModel" => "FormModel",
                                   "getWebsiteInfoModel" => "WebsiteInfoModel",
                                   "getRatingModel" => "RatingModel",              
                                   "getUserInfoModel" => "UserInfoModel"];

    // getLoginModel
    public static function __callStatic($method, $args)
    {
        if (array_key_exists($method, self::$modelinfo));
        {
        $model = substr($method, 3);
        $file = self::$modelinfo[$method];
        require_once "./src/models/$file.php";
        return self::initializeModel($model);
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