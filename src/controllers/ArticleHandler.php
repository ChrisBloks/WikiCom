<?php

namespace Wiki\controllers;

// use Wiki\tools\interfaces\iValidator;
use Wiki\tools\traits\tSingleton,
Wiki\models\ModelSelector,
Wiki\controllers\validationHandler;
use Wiki\tools\utils\HtmlUtils;


/**
 * Handler (controller) class for validating/executing all article-related requests
 * @uses tSingleton
 */
class ArticleHandler
{
    use tSingleton;

    /**
     * Check if search inputs are valid. 
     * Performs basic validation and returns inputs if they are correct.
     * Stores errors in the (passed by reference) $response parameter.
     * @param array $response array containing the source page (string)
     * @param ValidationHandler $validator BaseValidator object for first line validation.
     * @return array|false
     */
    public function checkSearch(array &$response, ValidationHandler $validator): array|false
    {
        // Get all fields of the 'search' form
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        
        // Validate all fields
        $result = $validator->validateFields($field_info);

        if ($result) {
            return $result;
        } else {
            $response['userError'] = array_merge($response['userError'], $validator->getErrors()); // Get why validation failed
            return false;

        }
    }

    /**
     * Uses the validator to check if article submission fields are valid.
     * If results are good run further tests needed to have a valid article submission.
     * Stores errors in the (passed by reference) $response parameter.
     * @param array $response array containing the source page (string)
     * @param ValidationHandler $validator TextValidator object for first line validation.
     * @return array|false
     */
    public function handleArticleSubmission(array &$response, ValidationHandler $validator): array|false
    {
        // Get all fields of the 'editArticle' form
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);

        $result = $validator->validateFields($field_info);
        HtmlUtils::dump("result", $result);





        return $result;

    }
}
