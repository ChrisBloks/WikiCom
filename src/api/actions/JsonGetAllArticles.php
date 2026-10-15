<?php

namespace Wiki\api\actions;
use wiki\models\ModelSelector;

class JsonGetAllArticles extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchAllArticles();
        if (!$this->data){
            return false;
        }
        return true;
    }
}