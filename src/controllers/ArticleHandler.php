<?php

namespace Wiki\controllers;

// use Wiki\tools\interfaces\iValidator;
use Wiki\tools\traits\tSingleton,
Wiki\models\ModelSelector,
Wiki\controllers\validators\Validator;


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
     * @param Validator $validator BaseValidator object for first line validation.
     * @return array|false
     */
    public function checkSearch(array &$response, Validator $validator): array|false
    {
        // Get all fields of the 'search' form
        $field_info = ModelSelector::getFormModel()->fetchFieldInfo($response['page']);
        
        // Validate all fields
        $result = $validator->validateFields($field_info);

        if ($result) {
            return $result;
        } else {
            $response['userError'] = $validator->getErrors(); // Get why validation failed
            return false;

        }
    }
}
