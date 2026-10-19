<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector;
use Wiki\tools\utils\HtmlUtils;
use Wiki\api\actions\BaseJsonAction;

class JsonGetAllArticles extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchAllArticlesAPI();
        if (!$this->data){
            return false;
        }
        return true;
    }
}