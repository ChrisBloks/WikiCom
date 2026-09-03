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
     * @param array $validation_result array containing the source page (string)
     * @return array|false
     */
    public function handleArticleSubmission(array $validation_result, string $article_id, string $user_id): array|false
    {
        ModelSelector::getArticleModel()->removeTagsFromArticle(article_id: $article_id);
        $isNewArticle = ($article_id == 0);

        // check for tags if they already exist
        $new_article_tags = [];
        foreach ($validation_result['field_inputs']['existing_tag'] as $key => $value) {
            if ((int) $value === 0) {
                $tagcheck = ModelSelector::getArticleModel()->checkTagExists(tag_name: $key);
                if (empty($tagcheck)) {
                    $value = ModelSelector::getArticleModel()->addNewTag(tag_name: $key);
                } else {
                    $value = $tagcheck['id'];
                }
                $validation_result['field_inputs']['existing_tag'][$key] = $value;
            }

            if ($isNewArticle) {
                $new_article_tags[] = $value;
            } else {
                ModelSelector::getArticleModel()->addTagToArticle(article_id: $article_id, tag_id: $value);
            }
        }

        //check if title already exists
        $titlecheck = ModelSelector::getArticleModel()->checkTitleExists(title_name: $validation_result['field_inputs']['title']);

        if ($titlecheck === false) {
            $validation_result['ok'] = false;
            $validation_result['user_error'][] = "Something went wrong with the server! contact Marius";
        } elseif ($isNewArticle) {
            if (!empty($titlecheck)) {
                $validation_result['ok'] = false;
                $validation_result['user_error'][] = "Title already exists, please choose a different title!";
            }
        } else {
            // extra case to make sure the title that exists isn't the article itself
            if (!empty($titlecheck) && $titlecheck['id'] != $article_id) {
                $validation_result['ok'] = false;
                $validation_result['user_error'][] = "Title already exists, please choose a different title!";
            }
        }

        // check if image file exists and if it does upload it
        if ($validation_result['ok'] && isset($validation_result['field_inputs']['filevar'])) {
            $target_dir = \Config::ARTICLEIMGPATH;
            $filevar = $validation_result['field_inputs']['filevar'];
            $filetype = strtolower(pathinfo($filevar['name'], PATHINFO_EXTENSION));
            $filename = 'article_' . $article_id . '.' . $filetype;
            $target_file = $target_dir . $filename;

            if (!move_uploaded_file($filevar["tmp_name"], $target_file)) {
                $validation_result['ok'] = false;
                $validation_result['user_error'][] = "Sorry, there was an error uploading your file.";
            } else {
                $validation_result['field_inputs']['articleimg'] = $filename;
            }
        }

        // all checks ok means the article can be saved
        if ($validation_result['ok']) {
            // new article save
            if ($isNewArticle) {
                $new_article_id = ModelSelector::getArticleModel()->saveNewArticleInfo(
                    article_title: $validation_result['field_inputs']['title'],
                    article_summary: $validation_result['field_inputs']['summary'],
                    article_codeBlock: $validation_result['field_inputs']['codeBlock'] ?? '',
                    imgFileName: $validation_result['field_inputs']['articleimg'] ?? '',
                    user_id: $user_id
                );

                if ($new_article_id === false) {
                    $validation_result['ok'] = false;
                    $validation_result['user_error'][] = 'Something went wrong while saving the article. Please try again later.';
                } else {
                    $validation_result['field_inputs']['new_article_id'] = $new_article_id;
                    foreach ($new_article_tags as $id) {
                        ModelSelector::getArticleModel()->addTagToArticle(article_id: $new_article_id, tag_id: $id);
                    }
                }
                //existing article save
            } else {
                // if no new image given, keep the article's existing image
                if (empty($validation_result['field_inputs']['articleimg'])) {
                    $article_info = ModelSelector::getArticleModel()->fetchArticleById($article_id);
                    $validation_result['field_inputs']['articleimg'] = isset($article_info['imgFileName']) ? $article_info['imgFileName']:'';
                }

                $update_result = ModelSelector::getArticleModel()->saveExistingArticleInfo(
                    article_id: $article_id,
                    article_title: $validation_result['field_inputs']['title'],
                    article_summary: $validation_result['field_inputs']['summary'],
                    article_codeBlock: $validation_result['field_inputs']['codeBlock'] ?? '',
                    imgFileName: $validation_result['field_inputs']['articleimg'] ?? '',
                    user_id: $user_id
                );

                if ($update_result === false) {
                    $validation_result['ok'] = false;
                    $validation_result['user_error'][] = 'Something went wrong while updating the article. Please try again later.';
                }
            }
        }
        return $validation_result;
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
