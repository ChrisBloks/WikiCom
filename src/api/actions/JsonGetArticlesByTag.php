<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector;

class JsonGetAllArticlesByTag extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByTag();
        if (!$this->data){
            return false;
        }
        return true;
    }
}