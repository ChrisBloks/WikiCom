<?php

namespace Wiki\controllers;

// use Wiki\tools\interfaces\iValidator;
use Wiki\tools\traits\tSingleton,
Wiki\models\ModelSelector,
Wiki\controllers\validationHandler;
use Wiki\tools\utils\HtmlUtils;
use Exception;


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
     * @param array $validation_result array containing the source page (string)
     * @return array|false
     */
    public function handleArticleSubmission(array $validation_result, int $article_id): array|false
    {
        ModelSelector::getArticleModel()->removeTagsFromArticle(article_id: $article_id);

        foreach ($validation_result['field_inputs']['existing_tag'] as $key => $value) {
            if ((int) $value === 0) {
                //check if tag exists in the database
                $tagcheck = ModelSelector::getArticleModel()->checkTagExists(tag_name: $key);
                // If it does not exist, add to database
                if (empty($tagcheck)) {
                    // give id back to the array
                    $tag_id = ModelSelector::getArticleModel()->addNewTag(tag_name: $key);
                    $validation_result['field_inputs']['existing_tag'][$key] = $tag_id;
                    ModelSelector::getArticleModel()->addTagToArticle(article_id: $article_id, tag_id: $tag_id);
                }
            } else {
                ModelSelector::getArticleModel()->addTagToArticle(article_id: $article_id, tag_id: $value);
            }
        }

        // new article submission, check if title already exists
        if ($article_id == 0) {
            $titlecheck = ModelSelector::getArticleModel()->checkTitleExists(title_name: $validation_result['field_inputs']['title']);

            if ($titlecheck === false) {
                $validation_result['ok'] = false;
                $validation_result['user_error'][] = "Something went wrong with the server! contact Marius";
            }

            if (!empty($titlecheck)) {
                $validation_result['ok'] = false;
                $validation_result['user_error'][] = "Title already exists, please choose a different title!";
            }
        }
        // existing article submission, check if title already exists and if it exists check if it has 
        // the same id as the current article being edited. If not, title already exists and is invalid.
        else {
            $titlecheck = ModelSelector::getArticleModel()->checkTitleExists(title_name: $validation_result['field_inputs']['title']);

            if ($titlecheck === false) {
                $validation_result['ok'] = false;
                $validation_result['user_error'][] = "Something went wrong with the server! contact Marius";
            }

            if (!empty($titlecheck) && $titlecheck['id'] != $article_id) {
                $validation_result['ok'] = false;
                $validation_result['user_error'][] = "Title already exists, please choose a different title!";
            }
        }

        return $validation_result;

    }

    /** WIP
     * handles deletion of user articles from database
     * @param int $article_id id of article to be deleted
     * @param int $userId id of user
     * @return int | false
    */
    public function handleDeleteArticle(int $article_id, int $userId): int|false
    {

        //check whether the article is owned by the userId (double check)
        $article = ModelSelector::getArticleModel()->fetchArticleById($article_id);
        if (!$article) {
            throw new Exception("Article not found");
        }
        if ($userId != $article['user_id']) {
            throw new Exception("Article does not match to user!");
        }

        $result = ModelSelector::getArticleModel()->deleteArticle($article_id);
        // PDO should return either false (failed) or an row count for rows deleted

        if ($result === false) {
            // DB query failure
            return false;
        }

        if ($result === 0) {
            // query runs but cant find article id
            return false;
        }
        // query succesful, return rowcount
        return $result;
    }



}
