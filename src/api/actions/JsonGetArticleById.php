<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
    Wiki\tools\utils\Utils;
use Wiki\tools\utils\HtmlUtils;

class JsonGetArticleById extends BaseJsonAction
{
    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchArticleByIdAPI(article_id:$this->request['articleId']);
        if (!$this->data){
            return false;
        }
        return true;
    }
}