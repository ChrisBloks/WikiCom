<?php
/* ModelSelector
*  Danny
*  08/2026
*  ModelSelector class requires the correct file and also initializes the needed model
*/

namespace Wiki\models;

abstract class ModelSelector
{
    protected static $modellist = [];
    protected static $modelinfo = [
        "getArticleModel" => "Wiki\models\ArticleModel",
        "getFormModel" => "Wiki\models\FormModel",
        "getWebsiteInfoModel" => "Wiki\models\WebsiteInfoModel",
        "getRatingModel" => "Wiki\models\RatingModel",
        "getUserInfoModel" => "Wiki\models\UserInfoModel"
    ];

    /*
    * __callstatic is a build in function that runs when one calls a static function from a class that doesn't exists. This
    * function creates based on the look upinfo the correct model. This way you can call up a nonexisting function to start a instance of a class
    *
    * @params $method = the name of the function, $args are the arguments not needed here.
    */
    public static function __callStatic(string $method, array $args): BaseModel
    {
        if (array_key_exists($method, self::$modelinfo)); {
            $model = substr($method, 3);
            $file = self::$modelinfo[$method];
            // require_once "./src/models/$file.php";
            return self::initializeModel($file);
        }
    }

    /*
    * singleton
    */
    public static function initializeModel(string $model): BaseModel
    {
        if (isset(self::$modellist[$model]) === false) {
            self::$modellist[$model] = new $model();
        }
        return self::$modellist[$model];
    }
}
