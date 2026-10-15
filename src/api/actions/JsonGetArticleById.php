<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
    Wiki\tools\utils\Utils;

class JsonGetArticleById extends BaseJsonAction
{
    protected function _getData(): bool
    {
        $article_id = Utils::getRequestVar('articleId', false);
        $this->data = ModelSelector::getArticleModel()->fetchArticleById(article_id:$article_id);
        if (!$this->data){
            return false;
        }
        return true;
    }
}