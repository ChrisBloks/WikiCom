<?php

namespace Wiki\api\actions;
use wiki\models\ModelSelector;

class JsonGetArticleById extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchArticleById();
        if (!$this->data){
            return false;
        }
        return true;
    }
}