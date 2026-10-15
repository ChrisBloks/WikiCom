<?php

namespace Wiki\api\actions;
use wiki\models\ModelSelector;

class JsonGetArticlesByUserTag extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByTagAndUserId();
        if (!$this->data){
            return false;
        }
        return true;
    }
}