<?php

namespace Wiki\api\actions;
use wiki\models\ModelSelector;

class JsonGetAllArticlesByUser extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByUserID();
        if (!$this->data){
            return false;
        }
        return true;
    }
}