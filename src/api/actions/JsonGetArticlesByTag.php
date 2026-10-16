<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
    Wiki\tools\utils\Utils;
use Wiki\tools\utils\HtmlUtils;

class JsonGetArticlesByTag extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByTag(tag_name:$this->request['tag']);
        if (!$this->data){
            return false;
        }
        return true;
    }
}