<?php
/* ModelSelector
*  Danny
*  08/2026
*  ModelSelector class requires the correct file and also initializes the needed model
*/
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

    /*
    * __callstatic is a build in function that runs when one calls a static function from a class that doesn't exists. This
    * function creates based on the look upinfo the correct model. This way you can call up a nonexisting function to start a instance of a class
    *
    * @params $method = the name of the function, $args are the arguments not needed here.
    */ 
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

    /*
    * singleton
    */ 
    public static function initializeModel($model)
    {
        if (isset(self::$modellist[$model]) === false) 
        {
            self::$modellist[$model] = new $model();
        }
        return self::$modellist[$model];
    }
}