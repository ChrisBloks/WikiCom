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
     * TODO: is this function still required?
     * @param array $validation_result array containing the source page (string).
     * @return array|false
     */
    public function checkSearch(array $validation_result): array|false
    {
        // Currently no page-specific validation required
        return $validation_result;
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

        $requiredFields = ['title', 'summary', 'existing_tag'];
        $result['userErr']=[];

        // Checks if all the required fields are filled in, if not add the error message to the userErr array
        foreach ($requiredFields as $field) {
            if (!empty($result['field_inputs'][$field])) {
                $result['ok'] = true;

            } else {
                $result['userErr'] = array_merge(
                    $result['userErr'],
                    $validator->getErrors()[$field] ?? []
                );
            }
        }

        return $result;

    }
}
