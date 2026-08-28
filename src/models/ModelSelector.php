<?php
/* ModelSelector
*  Danny
*  08/2026
*  ModelSelector class requires the correct file and also initializes the needed model
*/

namespace Wiki\models;

/**
 * Abstract class for initializing and accessing model objects.
 * @var array $modellist array of currently initialized model ['model_name' => ModelObject]
 * @var array $modelinfo array of mappings ['method_name' => 'model_class_name']
 */
abstract class ModelSelector
{
    protected static array $modellist = [];
    protected static array $modelinfo = [
        "getArticleModel" => "Wiki\models\ArticleModel",
        "getFormModel" => "Wiki\models\FormModel",
        "getWebsiteInfoModel" => "Wiki\models\WebsiteInfoModel",
        "getRatingModel" => "Wiki\models\RatingModel",
        "getUserInfoModel" => "Wiki\models\UserInfoModel"
    ];

    /**
     * Overwrites the built-in __callStatic function to return a model object given one of the method names given in @var $this->modelinfo.
     * EXAMPLE: ModelSelector::getArticleModel() wil result in an ArticleModel object despite the method 'getArticleModel' being undefined.
     * @param string $method name of the function requesting a model (get[ModelName]).
     * @param array $args arguments to give $method. Necessary parameter of __callStatic and not relevant here.
     * @return BaseModel
     */
    public static function __callStatic(string $method, array $args): BaseModel
    {
        if (array_key_exists($method, self::$modelinfo)); {
            $model_name = self::$modelinfo[$method];
            // require_once "./src/models/$file.php";
            return self::initializeModel($model_name);
        }
    }

    /**
     * Initalizes the given model if it is not yet set and returns the model object.
     * @param string $model_name
     * @return BaseModel
     */
    public static function initializeModel(string $model_name): BaseModel
    {
        if (isset(self::$modellist[$model_name]) === false) {
            self::$modellist[$model_name] = new $model_name();
        }
        return self::$modellist[$model_name];
    }
}
