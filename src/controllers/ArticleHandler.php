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
    public function handleArticleSubmission(array $validation_result, string $article_id, string $user_id): array|false
    {
        ModelSelector::getArticleModel()->removeTagsFromArticle(article_id: $article_id);

        // check for tags if they already exist
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

        // new article submission, check if title already exists and if it does not exits start save info
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

            if ($validation_result['ok']) {
                // Adds new article to the data base and returns the new article id, which is stored in the response array
                if ($article_id == 0) {
                    $new_article_id = ModelSelector::getArticleModel()->saveNewArticleInfo(
                        article_title: $validation_result['field_inputs']['title'],
                        article_summary: $validation_result['field_inputs']['summary'],
                        article_codeBlock: $validation_result['field_inputs']['codeBlock'] ?? '',
                        imgFileName: $validation_result['field_inputs']['articleimg'] ?? '',
                        user_id: $user_id
                    );
                    if ($new_article_id === false) {
                        $validation_result['user_error'][] = 'Something went wrong while saving the article. Please try again later.';
                    } else {
                        $validation_result['field_inputs']['new_article_id'] = $new_article_id;
                        $this->response['page'] = 'article';
                    }
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

            // checks if image file exists and if it exists create path and upload image
            if (isset($validation_result['field_inputs']['filevar'])) {
                // Construct image file path
                $target_dir = \Config::ARTICLEIMGPATH;
                $filevar = $validation_result['field_inputs']['filevar'];
                $filetype = strtolower(pathinfo($filevar['name'], PATHINFO_EXTENSION));
                $filename = 'article_' . $article_id . '.' . $filetype . '';
                $target_file = $target_dir . $filename;

                // uploading image
                if (!move_uploaded_file($filevar["tmp_name"], $target_file)) {
                    $validation_result['ok'] = false;
                    $validation_result['user_error'][] = "Sorry, there was an error uploading your file.";
                } else {
                    $validation_result['field_inputs']['articleimg'] = $filename;
                }
            }


            if ($validation_result['ok']) {
                //if no article image given check if article already has an image, if so keep it.
                if (empty($validation_result['field_inputs']['articleimg'])) {
                    $article_info = ModelSelector::getArticleModel()->fetchArticleById($this->response['editArticleID']);
                    $validation_result['field_inputs']['articleimg'] = $article_info['imgFileName'];
                }

                // Updates existing article in the database
                $update_result = ModelSelector::getArticleModel()->saveExistingArticleInfo(
                    article_id: $article_id,
                    article_title: $validation_result['field_inputs']['title'],
                    article_summary: $validation_result['field_inputs']['summary'],
                    article_codeBlock: $validation_result['field_inputs']['codeBlock'] ?? '',
                    imgFileName: $validation_result['field_inputs']['articleimg'] ?? '',
                    user_id: $user_id
                );
                if ($update_result === false) {
                    $validation_result['user_error'][] = 'Something went wrong while updating the article. Please try again later.';

                }
            } else {
                $validation_result['user_error'] = array_merge($validation_result['user_error'], $validation_result['user_error']);
            }

            return $validation_result;

        }
    }

    /** WIP
     * handles deletion of user articles from database
     * @param int $article_id id of article to be deleted
     * @param int $userId id of user
     * @return void
     */
    public function handleDeleteArticle(int $article_id, int $userId): int|false
    {

        //check whether the article is owned by the userId (double check)
        $article = ModelSelector::getArticleModel()->fetchArticleById($article_id);
        if (!$article) {
            throw new \Exception("Article not found");
        }
        if ($userId != $article['user_id']) {
            throw new \Exception("Article does not match to user!");
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
