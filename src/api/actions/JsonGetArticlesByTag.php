<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
    Wiki\tools\utils\Utils;
use Wiki\tools\utils\HtmlUtils;

class JsonGetArticlesByTag extends BaseJsonAction{


    protected function _getData(): bool
    {
        $tag = Utils::getRequestVar('tag', false);
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByTag(tag_name:$tag);
        if (!$this->data){
            return false;
        }
        return true;
    }
}